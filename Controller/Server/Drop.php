<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/20
 * Time: 下午6:26
 */

namespace Controller\Server;

use Carlosocarvalho\SimpleInput\Input\Input;
use Common\ItemHelper;
use Common\ServerHelper;
use Kernel\Config;
use Kernel\DB;
use Kernel\Response;
use PHPZip\Zip\File\Zip;

class Drop
{
    public function manage() {

        $map_items = ItemHelper::map_items();

        $map_mob_drop = collect(DB::connection()->get('map_drop_mob', null, [
            'ep',
            'name',
            'name_zh',
            'area',
            '`order` as disp_order',
        ]))->groupBy('ep')->map(function ($item) {
            return $item->sortBy('area')->groupBy('area')->map(function ($item) {
                return $item->sortBy('disp_order');
            });
        })->toArray();

        // 'hash' => 'dc5d7fbf04541ccab045ad76e4c0c72f',
        // 'ep' => 0,
        // 'dif' => 1,
        // 'sec' => 1,
        // 'type' => 1,
        // 'order' => 41,
        // 'area' => 3,
        // 'lv' => NULL,
        // 'name' => 'Dimenian/Arlan',
        // 'rate' => 201,
        // 'item' => '000605',
        // 'update_at' => '2017-10-19 11:30:03',
        // 'item_name' => 'VARISTA',
        // 'item_name_zh' => '麻醉枪',
        $mob_drop = collect(DB::connection()->where('type', 1)->where('name', 'Null', '!=')->get('item_drop'))
            ->map(function ($item) use (&$map_items) {

                $item['item_hex'] = $map_items[$item['item']]['hex'];
                $item['item_name'] = $map_items[$item['item']]['name'];
                $item['item_name_zh'] = $map_items[$item['item']]['name_zh']
                                        ??
                                        $map_items[$item['item']]['name']
                                        ??
                                        '？？？？';
                $item['rate_p'] = ServerHelper::DropRatePercent($item['rate']);

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

        $map_box_drop = [];
        $map_box_area_lv = Config::get('server.area');
        $box_drop = collect(DB::connection()->where('type', 0)->orderBy('`order`', 'asc')->get('item_drop'))
            ->map(function ($item) use (&$map_items, &$map_box_area_lv, &$map_box_drop) {

                $item['name'] = $map_box_area_lv[$item['ep']][$item['area']][1][$item['lv']][0];
                $item['name_zh'] = $map_box_area_lv[$item['ep']][$item['area']][1][$item['lv']][1];

                $item['item_hex'] = $map_items[$item['item']]['hex'];
                $item['item_name'] = $map_items[$item['item']]['name'];
                $item['item_name_zh'] = $map_items[$item['item']]['name_zh']
                                        ??
                                        $map_items[$item['item']]['name']
                                        ??
                                        '？？？？';

                $in = ['name' => $item['name'], 'name_zh' => $item['name_zh']];

                if (!isset($map_box_drop[$item['ep']][$item['area']]) ||
                    !in_array($in, $map_box_drop[$item['ep']][$item['area']])
                ) {
                    $map_box_drop[$item['ep']][$item['area']][] = $in;
                }

                $item['rate_p'] = ServerHelper::DropRatePercent($item['rate']);

                return $item;
            })
            ->groupBy('dif')
            ->map(function ($item) {
                return $item->groupBy('ep')->map(function ($item) {
                    return $item->groupBy('area')->map(function ($item) {
                        return $item->groupBy('sec')->map(function ($item) {
                            return $item->sortBy('order');
                        });
                    });
                });
            })
            ->toArray();

        $manage = 1;

        return Response::view('pages.drop.drop', compact('map_box_area_lv', 'mob_drop', 'map_mob_drop', 'box_drop', 'map_box_drop', 'manage'));

    }

    public function update() {

        $type = Input::post('type');

        switch ($type) {
            case 'mob':
                $hash = Input::post('hash');
                $item = Input::post('item');
                $rate = Input::post('rate') ?? 0;

                $hashes = explode(',', $hash);

                if (count($hashes) && $item && $rate > -1) {
                    if ($rate > 255) {
                        $rate = 255;
                    }
                    if ($rate < 0) {
                        $rate = 0;
                    }
                    $drop_info = DB::connection()->where('hash', $hashes, 'IN')->get('item_drop');
                    if (!$drop_info || count($drop_info) == 0) {
                        return Response::api(0, '无效的HASH');
                    }
                    $item_info = DB::connection()->where('hex', $item)->getOne('map_items');
                    if (!$item_info) {
                        return Response::api(0, '无效的物品');
                    }

                    if (DB::connection()
                        ->where('hash', collect($drop_info)->pluck('hash')->toArray(), 'IN')
                        ->update('item_drop', [
                            'item' => $item_info['hex'],
                            'rate' => $rate,
                        ])
                    ) {
                        return Response::api(0, '保存成功', [
                            'item'      => $item_info['hex'],
                            'rate'      => $rate,
                            'rate_p'    => ServerHelper::DropRatePercent($rate),
                            'item_info' => $item_info,
                        ]);
                    }
                }
                break;
            case 'box':
                $hash = Input::post('hash');
                $item = Input::post('item');
                $rate = Input::post('rate') ?? 0;
                $lv = Input::post('lv');

                if ($lv > -1 && $item && $rate > -1) {

                    $item_info = DB::connection()->where('hex', $item)->getOne('map_items');
                    if (!$item_info) {
                        return Response::api(0, '无效的物品');
                    }

                    $map_box_area_lv = Config::get('server.area');

                    if ($hash) {
                        $drop_info = DB::connection()->where('hash', $hash)->getOne('item_drop');
                        if (!$drop_info) {
                            return Response::api(0, '无效的HASH');
                        }
                        DB::connection()->where('hash', $hash)->delete('item_drop');
                    }
                    // 新增
                    $ek = Input::post('ep');
                    $dk = Input::post('dif');
                    $sk = Input::post('sec');
                    $ak = Input::post('area');
                    $order = DB::connection()
                        ->where('type', 0)
                        ->where('ep', $ek)
                        ->where('dif', $dk)
                        ->where('area', $ak)
                        ->where('sec', $sk)
                        ->orderBy('`order`', 'desc')
                        ->getValue('item_drop', '`order`', 1);

                    $order = $order > -1 ? $order + 1 : 0;

                    $data = [
                        'hash'  => md5($ek . $dk . $sk . 0 . $order),
                        'ep'    => $ek,
                        'dif'   => $dk,
                        'sec'   => $sk,
                        'type'  => 0,
                        'order' => $order,
                        'area'  => $ak,
                        'lv'    => $lv,
                        'name'  => null,
                        'rate'  => $rate,
                        'item'  => $item,
                    ];

                    if (DB::connection()->insert('item_drop', $data)) {
                        $data['name'] = $map_box_area_lv[$ek][$ak][1][$lv][0];
                        $data['name_zh'] = $map_box_area_lv[$ek][$ak][1][$lv][1];
                        $data['rate'] = $rate;
                        $data['rate_p'] = ServerHelper::DropRatePercent($rate);
                        $data['item'] = $item;
                        $data['item_info'] = $item_info;

                        return Response::api(0, '掉落添加成功', $data);
                    }
                }
                break;
        }

        return Response::api(-1, '保存失败');
    }

    public function box_delete() {
        $hash = Input::post('hash');
        if ($hash) {
            if (DB::connection()->where('hash', $hash)->where('type', 0)->delete('item_drop')) {
                return Response::api(0, '删除成功');
            }
        }

        return Response::api(-1, '删除失败');
    }

    public function public () {

        $cacheName = 'drop_public_' .
                     strtotime(DB::connection()->orderBy('updated_at', 'desc')->getValue('item_drop', 'updated_at', 1)
                               ??
                               '');

        if (Response::isCached($cacheName)) {
            return Response::cache($cacheName);
        }

        $map_items = collect(DB::connection()->get('map_items'))->keyBy('hex')->toArray();

        $map_mob_drop = collect(DB::connection()->get('map_drop_mob', null, [
            'ep',
            'name',
            'name_zh',
            'area',
            '`order` as disp_order',
        ]))->groupBy('ep')->map(function ($item) {
            return $item->sortBy('area')->groupBy('area')->map(function ($item) {
                return $item->sortBy('disp_order');
            });
        })->toArray();

        $mob_drop = collect(DB::connection()->where('type', 1)->where('name', 'Null', '!=')->get('item_drop'))
            ->map(function ($item) use (&$map_items) {
                $item['item_name'] = $map_items[$item['item']]['name'];
                $item['item_name_zh'] = $map_items[$item['item']]['name_zh']
                                        ??
                                        $map_items[$item['item']]['name']
                                        ??
                                        '？？？？';
                $item['rate_p'] = ServerHelper::DropRatePercent($item['rate']);

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

        $map_box_drop = [];
        $map_box_area_lv = Config::get('server.area');
        $box_drop = collect(DB::connection()
            ->where('type', 0)
            ->orderBy('`order`', 'asc')
            ->orderBy('area', 'asc')
            ->orderBy('lv', 'asc')
            ->get('item_drop'))->map(function ($item) use (&$map_items, &$map_box_area_lv, &$map_box_drop) {

            $item['name'] = $map_box_area_lv[$item['ep']][$item['area']][1][$item['lv']][0] . '\'s BOX';
            $item['name_zh'] = $map_box_area_lv[$item['ep']][$item['area']][1][$item['lv']][1] . ' 的箱子';

            $item['item_name'] = $map_items[$item['item']]['name'];
            $item['item_name_zh'] = $map_items[$item['item']]['name_zh']
                                    ??
                                    $map_items[$item['item']]['name']
                                    ??
                                    '？？？？';

            $in = ['name' => $item['name'], 'name_zh' => $item['name_zh']];

            if (!isset($map_box_drop[$item['ep']][$item['area']]) ||
                !in_array($in, $map_box_drop[$item['ep']][$item['area']])
            ) {
                $map_box_drop[$item['ep']][$item['area']][] = $in;
            }

            $item['rate_p'] = ServerHelper::DropRatePercent($item['rate']);

            return $item;
        })->groupBy('dif')->map(function ($item) {
            return $item->groupBy('ep')->map(function ($item) {
                return $item->groupBy('area')->map(function ($item) {
                    return $item->groupBy('name')->map(function ($item) {
                        return $item->groupBy('sec')->map(function ($item) {
                            return $item->sortBy('order');
                        });
                    });
                });
            });
        })->toArray();

        $manage = 0;

        return Response::view('pages.drop.drop', compact('mob_drop', 'map_mob_drop', 'box_drop', 'map_box_drop', 'manage'), $cacheName);
    }

    /**
     * 导出
     */
    public function export() {

        $server = Config::get('server');

        $zip = new Zip();

        $drop = [];

        $drop['box'] = collect(DB::connection()->where('type', 0)->get('item_drop'))
            ->groupBy('ep')
            ->map(function ($item) {
                return $item->groupBy('dif')->map(function ($item) {
                    return $item->groupBy('sec')->map(function ($item) {
                        return $item->sortBy('order')->map(function ($item) {
                            return sprintf("#\n%s\n%s\n%s", $item['lv'], $item['rate'], $item['item']);
                        })->implode("\n");
                    });
                });
            });

        $drop['mob'] = collect(DB::connection()->where('type', 1)->get('item_drop'))
            ->groupBy('ep')
            ->map(function ($item) {
                return $item->groupBy('dif')->map(function ($item) {
                    return $item->groupBy('sec')->map(function ($item) {
                        return $item->sortBy('order')->map(function ($item) {
                            return sprintf("#\n# %s\n%s\n%s", $item['name'], $item['rate'], $item['item']);
                        })->implode("\n");
                    });
                });
            });

        $time = time();

        foreach ($server['ep'] as $ek => $ep) {
            foreach ($server['dif'] as $dk => $dif) {
                foreach ($server['sec'] as $sk => $sec) {
                    foreach ($drop as $type => & $dropList) {
                        $zip->addFile(str_replace("\n", "\r\n", Response::view('template.' . $type . '_drop', [
                            'contents' => $dropList[$ek][$dk][$sk] ?? '',
                            'ep_disp'  => $ep[2],
                            'dif_disp' => $dif[0] . "\n",
                            'sec_disp' => $sec[0],
                        ])), sprintf('ep%d_%s_%d_%d.txt', $ep[0], $type, $dk, $sk), $time);
                    }
                }
            }
        }

        $zip->sendZip("drop.zip", "application/zip");

    }

    /**
     * 删除所有怪物的掉落
     *
     * @return string
     */
    public function remove_all_drop() {
        DB::connection()->where('type', 0)->delete('item_drop');
        DB::connection()->where('type', 1)->update('item_drop', ['item' => '000000', 'rate' => '0']);

        return Response::api(0, '所有掉落已清除');
    }

    /**
     * 导入掉落
     *
     * @return string
     */
    public function import() {

        $path = ROOT . '/__SERVER/import/drop';

        if (file_exists($path . '/imprted.lock')) {
            return Response::api(-1, '已导入，若要重新导入请先清空');
        }

        file_put_contents($path . '/imprted.lock', '');

        $map_mob_area = ServerHelper::mapMobArea();
        $map_box_area = ServerHelper::mapLvArea();

        $config = Config::get('server');

        $data = [];
        foreach ($config['ep'] as $ek => $ep) {
            foreach ($config['dif'] as $dk => $difficult) {
                foreach ($config['sec'] as $sk => $section) {
                    foreach ($config['type'] as $tk => $type) {
                        $file = $path . '/' . sprintf('ep%d_%s_%d_%d.txt', $ep[0], $type, $dk, $sk);
                        $content = array_slice(preg_split('/#\n/', file_get_contents($file)), 3);

                        foreach ($content as $order => $itemDrop) {
                            if ($itemDrop) {
                                $item = preg_split('/\n/', $itemDrop);

                                if ($tk == 1) { // mob
                                    $name = substr($item[0], 2);
                                    $lv = null;
                                    $area = $name != 'Null' && isset($map_mob_area[$ek][$name])
                                        ? $map_mob_area[$ek][$name] : null;
                                } else { // box
                                    $name = null;
                                    $lv = $item[0];
                                    $area = $map_box_area[$ek][$lv];
                                }

                                $data[] = [
                                    'hash'  => md5($ek . $dk . $sk . $tk . $order),
                                    'ep'    => $ek,
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

        return Response::api(0, '导入成功');
    }

    /**
     * 清理所有掉落
     *
     * @return string
     */
    public function clean() {
        $lockFile = ROOT . '/__SERVER/import/drop/imprted.lock';
        if (file_exists($lockFile)) {
            DB::connection()->rawQuery('truncate table `item_drop`');
            @unlink($lockFile);

            return Response::api(0, '清理成功');
        }

        return Response::api(-1, '无需清理');
    }
}