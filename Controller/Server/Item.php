<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/20
 * Time: 下午6:29
 */

namespace Controller\Server;

use Carlosocarvalho\SimpleInput\Input\Input;
use Kernel\DB;
use Kernel\Response;

class Item
{

    public function manage() {
        $data = DB::connection()->orderBy('hex', 'asc')->get('map_items');

        return Response::view('pages.item', compact('data'));
    }

    public function update() {
        $data = Input::post('data');

        $data = array_filter($data, function ($item) {
            return $item['changed'] == 1 ? true : false;
        });

        $db = DB::connection();

        $updated = 0;

        array_walk($data, function ($item, $hex) use ($db, &$updated) {
            if ($item['changed'] == 1) {
                unset($item['changed']);
                $db->where('hex', $hex)->update('map_items', $item);
                $updated++;
            }
        });

        return Response::api(0, '更新完成', $updated);
    }

    public function import() {
        $itemspmt = collect();
        collect(['armorpmt', 'shieldpmt', 'weaponpmt'])->each(function ($name) use (&$itemspmt) {
            $itemspmt = $itemspmt->merge(collect(file(ROOT .
                                                      '/__SERVER/item/' .
                                                      $name .
                                                      '.ini'))->mapWithKeys(function ($line) {
                list($hex, $name) = explode(',', rtrim($line, "\n"));

                return [substr($hex, 2) => trim($name, '"')];
            }));
        });

        $items = collect(file(ROOT . '/__SERVER/item/items.ini'))->mapWithKeys(function ($line) {
            list($hex, $name) = explode(',', rtrim($line, "\n"));

            return [(string)$hex => trim($name, '"')];
        });

        $items_zh = collect(file(ROOT . '/__SERVER/item/items_zh.ini'))->mapWithKeys(function ($line) {
            list($hex, $name) = explode(',', rtrim($line, "\n"));

            return [(string)$hex => trim($name, '"')];
        });

        $ITEMS = collect(array_unique(call_user_func_array('array_merge', [
            $itemspmt->keys()->toArray(),
            $items->keys()->toArray(),
            $items_zh->keys()->toArray(),
        ])))->sortBy(function ($hex) {
            return hexdec($hex);
        })->mapWithKeys(function ($item) use (&$items, &$items_zh, &$itemspmt) {
            return [
                $item => [
                    'hex'     => $item,
                    'name'    => $itemspmt->get($item) ?? $items->get($item),
                    'name_zh' => $items_zh->get($item),
                ],
            ];
        });

        if (!DB::connection()->getValue('map_items', 'count(hex)')) {
            DB::connection()->insertMulti('map_items', $ITEMS->values()->toArray());

            return Response::api(0, '一次性全量写入成功');
        } else {

            $ret = [
                'I' => [],
                'U' => [],
            ];

            $ITEMS->each(function ($item) use (&$ret) {
                if (DB::connection()->insert('map_items', $item)) {
                    $ret['I'][] = $item['hex'];
                } else {
                    $hex = $item['hex'];
                    unset($item['hex']);
                    DB::connection()->where('hex', $hex)->update('map_items', $item);
                    $ret['U'][] = $hex;
                }
            });

            return Response::api(0, '更新成功', $ret);
        }
    }
}