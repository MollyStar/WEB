<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/20
 * Time: 下午6:30
 */

namespace Controller\Server;

use Carlosocarvalho\SimpleInput\Input\Input;
use Kernel\Config;
use Kernel\DB;
use Kernel\Response;

class Mob
{

    public function manage() {
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

    public function import() {

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

    public function update() {
        $data = Input::post('data');

        $data = array_filter($data, function ($item) {
            return $item['changed'] == 1 ? true : false;
        });

        $db = DB::connection();

        $updated = 0;

        array_walk($data, function ($item, $id) use ($db, &$updated) {
            if ($item['changed'] == 1) {
                unset($item['changed']);
                $item['boss'] = $item['boss'] ?? 0;
                $item['special'] = $item['special'] ?? 0;
                $db->where('id', $id)->update('map_drop_mob', $item);
                $updated++;
            }
        });

        return Response::json(['code' => 0, 'msg' => '更新完成', 'response' => $updated]);
    }

    public function sync_simple_names() {
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

}