<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/20
 * Time: 下午6:29
 */

namespace Controller\Server;

use Carlosocarvalho\SimpleInput\Input\Input;
use Kernel\Config;
use Kernel\DB;
use Kernel\Response;

class Item
{
    /**
     * 物品管理
     *
     * @return string
     */
    public function manage() {
        $data = DB::connection()->orderBy('hex', 'asc')->get('map_items');

        return Response::view('pages.item.manage', compact('data'));
    }

    /**
     * 更新物品信息
     *
     * @return string
     */
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


    public function stat_boosts() {

        $map_items = collect(DB::connection()->get('map_items'))->keyBy('hex')->toArray();
        $map_stat_boosts = Config::get('server.stat_boosts');
        $map_stat = Config::get('server.stat');

        $itemspmt = [];

        collect([
            'armorpmt'  => 19,
            'shieldpmt' => 19,
            'weaponpmt' => 18,
        ])->each(function ($column, $name) use (&$itemspmt, &$map_items) {
            $itemspmt[$name] = collect(file(realpath(sprintf('%s/__SERVER/item/%s.ini', ROOT, $name))))->map(function ($line) use ($column, $name, &$map_items) {
                $data = explode(',', trim($line, "\n"));

                return [
                    'hex'         => $data[0],
                    'name_zh'     => $map_items[substr($data[0], 2)]['name_zh'],
                    'name'        => $data[1],
                    'stat_boosts' => $data[$column],
                    'type'        => $name,
                ];
            });
        });

        $data = collect($itemspmt)->flatten(1)->sortBy('stat_boosts')->groupBy('stat_boosts')->toArray();

        unset($data[0]);

        return Response::view('pages.item.stat_boosts', compact('data', 'map_stat_boosts', 'map_stat'));

    }

    public function tech_boosts() {
        $map_items = collect(DB::connection()->get('map_items'))->keyBy('hex')->toArray();
        $map_tech_boosts = Config::get('server.tech_boosts');
        $map_tech = Config::get('server.tech');
        $itemspmt = [];

        collect([
            'armorpmt'  => 20,
            'shieldpmt' => 20,
            'weaponpmt' => 30,
        ])->each(function ($column, $name) use (&$itemspmt, &$map_items) {
            $itemspmt[$name] = collect(file(realpath(sprintf('%s/__SERVER/item/%s.ini', ROOT, $name))))->map(function ($line) use ($column, $name, &$map_items) {
                $data = explode(',', trim($line, "\n"));

                return [
                    'hex'         => $data[0],
                    'name_zh'     => $map_items[substr($data[0], 2)]['name_zh'],
                    'name'        => $data[1],
                    'tech_boosts' => $data[$column],
                    'type'        => $name,
                ];
            });
        });

        $data = collect($itemspmt)->flatten(1)->sortBy('tech_boosts')->groupBy('tech_boosts')->toArray();

        unset($data[0]);

//        collect($data)->flatten(1)->each(function ($item) use (&$map_tech_boosts, &$map_tech) {
//            echo '| ' . join(' | ', [
//                    $item['hex'],
//                    $item['name_zh'],
//                    collect($map_tech_boosts[$item['tech_boosts']])->map(function ($item) use (&$map_tech) {
//                        return $map_tech[$item[0]][1] .
//                               ($item[1] > 0 ? '+' . ($item[1] * 100) . '%' : ($item[1] * 100) . '%');
//                    })->implode(' '),
//                ]) . ' |  |<br/>';
//        });
//
//        return;

        return Response::view('pages.item.tech_boosts', compact('data', 'map_tech', 'map_tech_boosts'));
    }

    /**
     * 导入
     *
     * @return string
     */
    public function import() {

        // 不能再用了
        // TODO 用修正对比导入取代
        return false;

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