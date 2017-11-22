<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/24
 * Time: 下午6:48
 */

namespace Controller\Server;

use Carlosocarvalho\SimpleInput\Input\Input;
use Codante\Binary\Binary;
use Common\AccountHelper;
use Common\CharacterHelper;
use Common\ItemHelper;
use Common\UserHelper;
use Kernel\Config;
use Kernel\DB;
use Kernel\Response;
use Model\Bank;
use Model\BankItem;
use Model\InventoryCollection;

class Account
{

    public function manage() {

        $account_list = collect(DB::connection()->get('account_data'))->map(function ($item) {
            $item['isonline'] = UserHelper::isOnline($item['guildcard']);
            $item['regtime'] = date('Y-m-d H:i:s', $item['regtime'] * 3600);

            return $item;
        })->toArray();

        return Response::view('pages.account', compact('account_list'));
    }

    //    public function manage_2() {
    //        $character_list = collect(DB::connection()
    //            ->join('account_data as ad', 'ad.guildcard = cd.guildcard', 'INNER')
    //            ->orderBy('ad.guildcard', 'asc')
    //            ->get('character_data as cd'))->map(function ($item) {
    //
    //            $item['data'] = bin2hex($item['data']);
    //            $item['header'] = bin2hex($item['header']);
    //            $item['lasthwinfo'] = bin2hex($item['lasthwinfo']);
    //            $item['lastchar'] = bin2hex($item['lastchar']);
    //            $item['regtime'] = date('Y-m-d H:i:s', $item['regtime'] * 3600);
    //
    //            return $item;
    //        })->groupBy('guildcard')->map(function ($item) {
    //            $new_item = collect($item[0])->only([
    //                'guildcard',
    //                'username',
    //                'regtime',
    //                'lastip',
    //                'isgm',
    //                'isbanned',
    //                'islogged',
    //                'isactive',
    //            ]);
    //            $new_item['characters'] = collect($item)->map(function ($item) {
    //                return collect($item)->only(['slot', 'data', 'header']);
    //            });
    //            $new_item['character_num'] = count($new_item['characters']);
    //
    //            return $new_item;
    //        })->toArray();
    //
    //        return Response::view('pages.character', compact('character_list'));
    //    }

    public function common_bank() {
        $guildcard = Input::get('guildcard');
        if ($guildcard > 0 && $user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data')) {
            $bank = AccountHelper::common_bank($guildcard);

            $bank_use = $bank->used();
            $bank_meseta = $bank->getMST();
            $items = $bank->items(true);
            $codes = $bank->itemsHEX();

            $map_items = collect(DB::connection()->where('hex', $codes, 'IN')->get('map_items'))
                ->keyBy('hex')
                ->toArray();

            return Response::view('pages.character.commonbank', compact('items', 'map_items', 'bank_use', 'bank_meseta', 'user'));
        }
    }

    public function common_bank_save() {
        $data = Input::post('data');
        $guildcard = Input::post('guildcard') ?? 0;
        $mst = Input::post('mst') ?? 0;

        if (!$guildcard) {
            return Response::api(-1, '无效的用户');
        }

        $user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data');
        if (!$user) {
            return Response::api(-2, '无效的用户');
        }

        if (UserHelper::isOnline($user['guildcard'])) {
            return Response::api(-1, '用户处于在线状态，无法保存');
        }

        $bank = Bank::make();
        $bank->setMST($mst);
        if (!empty($data)) {
            collect($data)->each(function ($item) use (&$bank) {
                $bank->addItem(BankItem::make($item['code'], $item['num']));
            });
        }

        if (DB::connection()->where('guildcard', $user['guildcard'])->getOne('bank_data')) {
            if (DB::connection()
                ->where('guildcard', $user['guildcard'])
                ->update('bank_data', ['data' => $bank->toBin()])
            ) {
                return Response::api(0, '保存成功');
            }
        } else {
            if (DB::connection()->insert('bank_data', ['guildcard' => $user['guildcard'], 'data' => $bank->toBin()])) {
                return Response::api(0, '公共银行初始化完成，保存成功');
            }
        }

        return Response::api(-1, '保存失败');
    }

    public function character() {
        $guildcard = Input::get('guildcard') ?? 0;

        if ($guildcard == 0) {
            return Response::redirect('/account');
        }

        $user_info = UserHelper::getUserInfoByGuildcard($guildcard);

        $character_list = DB::connection()->where('guildcard', $guildcard)->get('character_data', null, [
            'guildcard',
            'slot',
            'data',
        ]);

        if ($character_list) {

            $map_sec = Config::get('server.sec');
            $map_class = Config::get('server.class');

            $character_list = collect($character_list)->map(function ($character) use (&$map_sec, &$map_class) {

                $data = collect(Binary::Parser(Config::get('DATA_STRUCTURE.CHARDATA'), Binary::Stream($character['data']))
                    ->data())->only(['name', 'level', 'sectionID', 'playTime', '_class'])->toArray();

                $data['level'] += 1;
                $data['sectionID'] = hexdec($data['sectionID']);
                $data['sec'] = $map_sec[$data['sectionID']];
                $data['_class'] = hexdec($data['_class']);
                $data['class'] = $map_class[$data['_class']];
                $data['name'] = CharacterHelper::decode_name($data['name']);
                $data['playTime'] = intval(ceil($data['playTime'] / 3600));

                $character['data'] = $data;

                return $character;
            })->sortBy('slot')->values()->toArray();
        }

        return Response::view('pages.character.manage', compact('character_list', 'user_info'));
    }

    public function character_detail() {

        $guildcard = Input::get('guildcard') ?? 0;
        $slot = Input::get('slot');

        if ($guildcard == 0) {
            return Response::redirect('/account');
        }

        if ($slot === false) {
            return Response::redirect('/account/character?guildcard=' . $guildcard);
        }

        $map_sec = Config::get('server.sec');
        $map_class = Config::get('server.class');

        $chardata = DB::connection()
            ->where('guildcard', $guildcard)
            ->where('slot', $slot)
            ->getValue('character_data', 'data');

        if (!$chardata) {
            return Response::redirect('/account/character?guildcard=' . $guildcard);
        }

        $chardata = collect(Binary::Parser(Config::get('DATA_STRUCTURE.CHARDATA'), Binary::Stream($chardata))->data());

        $info = $chardata->only(['name', 'level', 'sectionID', 'playTime', '_class', 'guildCard'])->toArray();

        $info['level'] += 1;
        $info['sectionID'] = hexdec($info['sectionID']);
        $info['sec'] = $map_sec[$info['sectionID']];
        $info['_class'] = hexdec($info['_class']);
        $info['class'] = $map_class[$info['_class']];
        $info['name'] = CharacterHelper::decode_name($info['name']);
        $info['playTime'] = intval(ceil($info['playTime'] / 3600));

        $bank = Bank::make();
        $bank->setMST($chardata['bankMeseta']);
        $bank->fillInventory($chardata['bankInventory']);

        $inventory = InventoryCollection::make();
        $inventory->setMST($chardata['meseta']);
        $inventory->fillInventory($chardata['inventory']);

        $map_items = ItemHelper::map_items();

        return Response::view('pages.character.detail', compact('info', 'bank', 'inventory', 'map_items'));
    }
}