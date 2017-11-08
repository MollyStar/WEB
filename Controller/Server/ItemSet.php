<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/20
 * Time: 下午6:29
 */

namespace Controller\Server;

use Carlosocarvalho\SimpleInput\Input\Input;
use Common\ItemHelper;
use Common\UserHelper;
use Kernel\DB;
use Kernel\Response;
use Model\BankItem as ItemModel;
use Model\BankItem;
use Model\ItemSet as ItemSetModel;
use \Exception;

class ItemSet
{
    /**
     * 套装
     *
     * @return string
     */
    public function list() {

        $codes = [];

        $data = collect(DB::connection()->orderBy('updated_at', 'desc')->get('item_set'))
            ->map(function ($item) use (&$codes) {
                $item['items'] = collect(($items = json_decode($item['items'], true)) ? $items : [])
                    ->map(function ($item) use (&$codes) {
                        $item = new BankItem($item[0], $item[1]);
                        $codes[] = $item->hex;

                        return [
                            'hex'  => $item->hex,
                            'code' => join(',', str_split($item->code, 8)),
                            'num'  => $item->num,
                        ];
                    })
                    ->toArray();

                $item['items_count'] = count($item['items']);

                return $item;
            })
            ->toArray();

        $codes = array_unique($codes);
        $map_items = [];
        if ($codes) {
            $map_items = collect(DB::connection()->where('hex', $codes, 'IN')->get('map_items'))
                ->keyBy('hex')
                ->toArray();
        }

        return Response::view('pages.item.set.list', compact('data', 'map_items'));
    }

    /**
     * 套装详情
     *
     * @return string
     */
    public function detail() {
        $name = Input::get('name');

        $data = null;
        if ($name) {

            $codes = [];

            $data = DB::connection()->where('name', $name)->getOne('item_set');
            $data['items'] = collect(($items = json_decode($data['items'], true)) ? $items : [])
                ->map(function ($item) use (&$codes) {
                    $item = new BankItem($item[0], $item[1]);
                    $codes[] = $item->hex;

                    return [
                        'hex'    => $item->hex,
                        'code'   => join(',', str_split($item->code, 8)),
                        'num'    => $item->num,
                        'detail' => $item->detail,
                    ];
                })
                ->toArray();

            $codes = array_unique($codes);
            $map_items = [];
            if ($codes) {
                $map_items = collect(DB::connection()->where('hex', $codes, 'IN')->get('map_items'))
                    ->keyBy('hex')
                    ->toArray();
            }
        }

        return Response::view('pages.item.set.detail', compact('data', 'map_items'));
    }

    /**
     * 保存套装信息
     *
     * @return string
     */
    public function save() {
        $name = Input::post('name');
        $description = Input::post('description') ?? '';
        $mst = Input::post('mst') ?? 0;
        $data = Input::post('data');

        if (!$name) {
            return Response::api(-1, '请输入名称');
        }

        $item_set = DB::connection()->where('name', $name)->getOne('item_set');

        if (empty($data)) {
            $data = [];
        } else {
            $data = collect($data)->map(function ($item) {
                $item = ItemModel::make($item['code'], $item['num']);

                if ($item->isValid()) {
                    return $item;
                }

                return null;
            })->filter()->sortBy(function ($item) {
                return hexdec($item->hex);
            })->values()->map(function ($item) {
                return [$item->code, $item->num];
            })->toArray();
        }

        if ($mst > 999999) {
            $mst = 999999;
        } elseif ($mst < -999999) {
            $mst = -999999;
        }

        if ($item_set) {
            $ret = DB::connection()->where('name', $name)->update('item_set', [
                'mst'         => $mst,
                'description' => $description,
                'items'       => json_encode($data),
            ]);
            $response = null;
        } else {
            $ret = DB::connection()->insert('item_set', [
                'name'        => $name,
                'mst'         => $mst,
                'description' => $description,
                'items'       => json_encode($data),
            ]);
            $response = $name;
        }

        if ($ret) {
            return Response::api(0, '保存成功', $response);
        }

        return Response::api(-1, '保存失败');
    }

    /**
     * 删除套装
     *
     * @return string
     */
    public function delete() {
        $name = Input::post('name');
        if ($name) {
            if (DB::connection()->where('name', $name)->delete('item_set')) {
                return Response::api(0, '删除成功');
            }
        }

        return Response::api(-1, '删除失败');
    }

    public function send() {
        $item_set = Input::post('item_set');
    }

    public function send_to_character_commonbank() {
        $item_set = Input::get('item_set');
        $guildcard = Input::get('guildcard') ?? 0;

        if ($guildcard == 0 || !$user = UserHelper::getUserInfoByGuildcard($guildcard)) {
            return Response::api(-1, '无效的帐号');
        }

        if (UserHelper::isOnline($user['guildcard'])) {
            return Response::api(-1, '请登出您的游戏帐号再尝试领取');
        }

        try {
            if (ItemHelper::send_items_to_commonbank($user['guildcard'], ItemSetModel::make($item_set))) {
                !$user['isgm'] && DB::connection()->insert('topic_record', [
                    'guildcard' => $user['guildcard'],
                    'name'      => 'NEWEST_PACKAGE',
                ]);

                return Response::api(0, '领取成功');
            }
        } catch (Exception $e) {
            return Response::api(-1, '领取失败，' . $e->getMessage());
        }
    }
}