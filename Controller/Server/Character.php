<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/24
 * Time: 下午6:48
 */

namespace Controller\Server;


use Carlosocarvalho\SimpleInput\Input\Input;
use Kernel\DB;
use Kernel\Response;
use Model\CommonBank;
use Model\CommonBankItem;

class Character
{
    public function manage() {
        $character_list = collect(DB::connection()
            ->join('account_data as ad', 'ad.guildcard = cd.guildcard', 'INNER')
            ->orderBy('ad.guildcard', 'asc')
            ->get('character_data as cd'))->map(function ($item) {
            $item['data'] = bin2hex($item['data']);
            $item['header'] = bin2hex($item['header']);
            $item['lasthwinfo'] = bin2hex($item['lasthwinfo']);
            $item['lastchar'] = bin2hex($item['lastchar']);
            $item['regtime'] = date('Y-m-d H:i:s', $item['regtime'] * 3600);

            return $item;
        })->groupBy('guildcard')->map(function ($item) {
            $new_item = collect($item[0])->only([
                'guildcard',
                'username',
                'regtime',
                'lastip',
                'isgm',
                'isbanned',
                'islogged',
                'isactive',
            ]);
            $new_item['characters'] = collect($item)->map(function ($item) {
                return collect($item)->only(['slot', 'data', 'header']);
            });
            $new_item['character_num'] = count($new_item['characters']);

            return $new_item;
        })->toArray();

        return Response::view('pages.character', compact('character_list'));
    }

    public function bank() {
        $guildcard = Input::get('guildcard');
        if ($guildcard > 0) {
            $user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data');

            if ($user) {

                $data = DB::connection()->where('guildcard', $guildcard)->getValue('bank_data', 'data');
                $bank = CommonBank::fromBin($data);

                $bank_use = $bank->USE;
                $bank_meseta = $bank->MST;
                $items = $bank->items(true);

                $codes = $bank->itemsHEX();

                $map_items = collect(DB::connection()->where('hex', $codes, 'IN')->get('map_items'))
                    ->keyBy('hex')
                    ->toArray();

                return Response::view('pages.character.commonbank', compact('items', 'map_items', 'bank_use', 'bank_meseta', 'user'));
            }
        }
    }

    public function bank_save() {
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

        if ($user['islogged']) {
            return Response::api(-2, '保存失败，用于处于在线状态');
        }

        $bank = CommonBank::make();
        $bank->setMST($mst);
        collect($data)->each(function ($item) use (&$bank) {
            $bank->addItem(CommonBankItem::make($item['code'], $item['num']));
        });

        if (DB::connection()->where('guildcard', $user['guildcard'])->update('bank_data', ['data' => $bank->toBin()])) {
            return Response::api(0, '保存成功');
        }

        return Response::api(-1, '保存失败');
    }
}