<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 下午10:42
 */

namespace Controller;

use Carlosocarvalho\SimpleInput\Input\Input;
use Common\MobHelper;
use Kernel\Config;
use Kernel\DB;
use Kernel\Response;

class Server
{

    public function mob() {
        $data = DB::connection()
            ->orderBy('ep', 'ASC')
            ->orderBy('area', 'ASC')
            ->orderBy('`order`', 'ASC')
            ->get('map_drop_mob');

        $epConf = Config::get('server.ep');
        $areaConf = Config::get('server.area');

        foreach ($data as &$item) {
            $item['ep_disp'] = $epConf[$item['ep']][0];
        }

        return Response::view('pages.mob', ['data' => $data, 'area' => $areaConf]);
    }

    public function mob_import() {

        if (0 < DB::connection()->getValue('map_drop_mob', 'count(name)')) {
            return '无需导入';
        }

        $res = DB::connection()
            ->where('type', 1)
            ->where('name', 'Null', '!=')
            ->groupBy('ep,name')
            ->orderBy('ep', 'ASC')
            ->orderBy('name', 'ASC')
            ->get('item_drop', null, ['name', 'ep']);

        DB::connection()->insertMulti('map_drop_mob', $res);

        return '导入成功';
    }

    public function mob_update() {
        $data = Input::post('data');

        $data = array_filter($data, function ($item) {
            return $item['changed'] == 1 ? true : false;
        });

        $db = DB::connection();

        $updated = 0;

        array_walk($data, function ($item, $id) use ($db, &$updated) {
            if ($item['changed'] == 1) {
                unset($item['changed']);
                $item['boss'] = isset($item['boss']) ? 1 : 0;
                $db->where('id', $id)->update('map_drop_mob', $item);
                $updated++;
            }
        });

        return Response::json(['code' => 0, 'msg' => '更新完成', 'response' => $updated]);
    }

    public function mob_sync_simple_names() {
        $data = DB::connection()->get('map_drop_mob', null, ['id', 'name', 'name_zh']);

        collect($data)->each(function ($item) {
            $g_name = explode('/', $item['name']);
            $g_name_zh = explode('/', $item['name_zh']);

            if (count($g_name) == 1) {
                $g_name[] = $g_name[0];
                $g_name_zh[] = $g_name_zh[0];
            }

            DB::connection()->where('id', $item['id'])->update('map_drop_mob', array_combine([
                'name_nhvh',
                'name_u',
                'name_nhvh_zh',
                'name_u_zh',
            ], array_merge($g_name, $g_name_zh)));
        });

        return Response::json(['code' => 0, 'msg' => '更新成功']);
    }

    public function drop() {

        $mob_drop = collect(DB::connection()->where('type', 1)->where('name', 'Null', '!=')->get('item_drop'))
            ->map(function ($item) {
                return $item;
            })
            ->groupBy('dif')
            ->map(function ($item) {
                return $item->groupBy('ep')->map(function ($item) {
                    return $item->groupBy('area')->map(function ($item) {
                        return $item->groupBy('name')->map(function ($item) {
                            return $item->sortBy('sec')->keyBy('sec');
                        });
                    });
                });
            })
            ->toArray();

        $map_mob_drop = collect(DB::connection()->get('map_drop_mob', null, [
            'ep',
            'name',
            'name_zh',
            'area',
            '`order` as disp_order',
        ]))->groupBy('ep')->map(function ($item) {
            return $item->groupBy('area')->map(function ($item) {
                return $item->sortBy('disp_order');
            });
        })->toArray();

        //        $box_drop = collect(DB::connection()->where('type', 0)->get('item_drop'));
        //
        //        $box_drop = $box_drop->groupBy('dif')->map(function ($difGroupItem) {
        //            return $difGroupItem->groupBy('');
        //        });

        return Response::view('pages.drop.drop', compact('mob_drop', 'map_mob_drop'));

    }

    public function drop_import() {
        $path = ROOT . '/__SERVER/drop';

        $map_mob_area = collect(DB::connection()->get('map_drop_mob', null, ['ep', 'name', 'area']))
            ->groupBy('ep')
            ->map(function ($item) {
                return array_combine($item->pluck('name')->toArray(), $item->pluck('area')->toArray());
            })
            ->all();

        //                dd($map_mob_area);

        if (file_exists($path . '/imprted.lock')) {
            return '已导入，若要重新导入请先<a href="/drop/clean" target="_blank">清空</a>';
        }

        file_put_contents($path . '/imprted.lock', '');

        $config = Config::get('server');

        $data = [];
        foreach ($config['ep'] as $epk => $ep) {
            foreach ($config['dif'] as $dk => $difficult) {
                foreach ($config['sec'] as $sk => $section) {
                    foreach ($config['type'] as $tk => $type) {
                        $file = $path . '/' . sprintf('ep%d_%s_%d_%d.txt', $ep[0], $type, $dk, $sk);
                        $content = array_slice(preg_split('/#\n/', file_get_contents($file)), 3);

                        foreach ($content as $order => $itemDrop) {
                            if ($itemDrop) {
                                $item = preg_split('/\n/', $itemDrop);

                                //                                if ($epk == 2 && $tk == 1 && $dk == 2 && $order = 41) {
                                //                                    dd($item[0]);
                                //                                }
                                if ($tk == 1) {
                                    $name = substr($item[0], 2);
                                    $lv = null;
                                    $area = $name != 'Null' && isset($map_mob_area[$epk][$name])
                                        ? $map_mob_area[$epk][$name] : null;
                                } else {
                                    $name = $area = null;
                                    $lv = $item[0];
                                }

                                $data[] = [
                                    'hash'  => md5($epk . $dk . $sk . $tk . $order),
                                    'ep'    => $epk,
                                    'dif'   => $dk,
                                    'sec'   => $sk,
                                    'type'  => $tk,
                                    'order' => $order,
                                    'area'  => $area,
                                    'lv'    => $lv,
                                    'name'  => $name,
                                    'rate'  => $item[1],
                                    'item'  => $item[2],
                                ];
                            }
                        }
                    }
                }
            }
        }

        DB::connection()->insertMulti('item_drop', $data);

        return '导入成功';
    }

    public function drop_clean() {
        $lockFile = ROOT . '/__SERVER/drop/imprted.lock';
        if (file_exists($lockFile)) {
            DB::connection()->rawQuery('truncate table `item_drop`');
            @unlink($lockFile);

            return '清理成功';
        }

        return '无需清理';
    }

    public function items() {

        $items = collect();

        collect(['armorpmt', 'shieldpmt', 'weaponpmt'])->each(function ($name) use (&$items) {
            $items = $items->merge(collect(file(ROOT .
                                                '/__SERVER/item/' .
                                                $name .
                                                '.ini'))->mapWithKeys(function ($line) {
                list($hex, $name) = explode(',', rtrim($line, "\n"));

                return [substr($hex, 2) => trim($name, '"')];
            }));
        });

        $items_zh = collect(file(ROOT . '/__SERVER/item/items_zh.ini'))->mapWithKeys(function ($line) {
            list($hex, $name) = explode(',', rtrim($line, "\n"));

            return [(string)$hex => trim($name, '"')];
        });

        $item_drop = collect(DB::connection()
            ->groupBy('item')
            ->orderBy('item', 'ASC')
            ->get('item_drop', null, ['item']))->map(function ($item) use (&$items, &$items_zh) {
            $item['name'] = $items->get($item['item']);
            $item['name_zh'] = $items_zh->get($item['item']);

            return $item;
        });

        dd($item_drop);
    }
}