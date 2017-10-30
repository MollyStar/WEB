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
use Model\CommonBankItem;

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

    public function item_set() {

        $codes = [];

        $data = collect(DB::connection()->orderBy('updated_at', 'desc')->get('item_set'))
            ->map(function ($item) use (&$codes) {
                $item['items'] = collect(($items = json_decode($item['items'], true)) ? $items : [])
                    ->map(function ($item) use (&$codes) {
                        $hex = substr($item[0], 0, 6);
                        $codes[] = $hex;

                        return ['hex' => $hex, 'code' => $item[0], 'num' => $item[1]];
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

        return Response::view('pages.item_set', compact('data', 'map_items'));
    }

    public function item_set_detail() {
        $name = Input::get('name');

        $data = null;
        if ($name) {

            $codes = [];

            $data = DB::connection()->where('name', $name)->getOne('item_set');
            $data['items'] = collect(($items = json_decode($data['items'], true)) ? $items : [])
                ->map(function ($item) use (&$codes) {
                    $hex = substr($item[0], 0, 6);
                    $codes[] = $hex;

                    return ['hex' => $hex, 'code' => $item[0], 'num' => $item[1]];
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

        return Response::view('pages.item_set_detail', compact('data', 'map_items'));
    }

    public function item_set_detail_save() {
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
                $item = CommonBankItem::make($item['code'], $item['num']);

                if ($item->isValid()) {
                    return [$item->code, $item->num];
                }

                return null;
            })->filter()->toArray();
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

    public function item_set_detail_delete() {
        $name = Input::post('name');
        if ($name) {
            if (DB::connection()->where('name', $name)->delete('item_set')) {
                return Response::api(0, '删除成功');
            }
        }

        return Response::api(-1, '删除失败');
    }
}