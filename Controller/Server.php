<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 下午10:42
 */

namespace Controller;

use Carlosocarvalho\SimpleInput\Input\Input;
use Kernel\Config;
use Kernel\DB;
use Kernel\Response;

class Server
{

    public function mob() {
        $data = DB::connection()->orderBy('ep', 'ASC')->orderBy('`order`', 'ASC')->get('mob');

        $epConf = Config::get('server.ep');
        $areaConf = Config::get('server.area');

        foreach ($data as &$item) {
            $item['ep_disp'] = $epConf[$item['ep']][0];
        }

        Response::view('pages.mob', ['data' => $data, 'area' => $areaConf]);
    }

    public function mob_import() {

        if (0 < DB::connection()->getValue("mob", "count(name)")) {
            exit('无需导入');
        }

        $res = DB::connection()
            ->where('type', 1)
            ->groupBy('name')
            ->orderBy('ep', 'ASC')
            ->orderBy('name', 'ASC')
            ->get('item_drop', null, ['name', 'ep']);

        DB::connection()->insertMulti('mob', $res);
        exit('导入成功');
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
                $db->where('id', $id)->update('mob', $item);
                $updated++;
            }
        });

        Response::json(['code' => 0, 'msg' => '更新完成', 'response' => $updated]);
    }

    public function drop() {

    }

    public function drop_import() {
        $path = ROOT . '/__SERVER/drop';

        if (file_exists($path . '/imprted.lock')) {
            echo '已导入，若要重新导入请先<a href="/drop/clean" target="_blank">清空</a>';

            return false;
        }

        file_put_contents($path . '/imprted.lock', '');

        $config = Config::get('server');

        $data = [];
        foreach ($config['ep'] as $epk => $ep) {
            foreach ($config['dif'] as $dk => $difficult) {
                foreach ($config['sec'] as $sk => $section) {
                    foreach ($config['type'] as $tk => $type) {
                        $file = $path . '/' . sprintf('ep%d_%s_%d_%d.txt', $ep, $type, $dk, $sk);
                        $content = array_slice(preg_split('/#\n/', file_get_contents($file)), 3);

                        foreach ($content as $order => $itemDrop) {
                            if ($itemDrop) {
                                $item = preg_split('/\n/', $itemDrop);
                                $name = $tk == 1 ? substr($item[0], 2) : null;
                                $lv = $tk == 1 ? null : $item[0];
                                $data[] = [
                                    'hash'  => md5($epk . $dk . $sk . $tk . $order . $name),
                                    'ep'    => $epk,
                                    'dif'   => $dk,
                                    'sec'   => $sk,
                                    'type'  => $tk,
                                    'order' => $order,
                                    //'area'  => $area,
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

        exit('导入成功');
    }

    public function drop_clean() {
        $lockFile = ROOT . '/__SERVER/drop/imprted.lock';
        if (file_exists($lockFile)) {
            DB::connection()->rawQuery('truncate table `item_drop`');
            @unlink($lockFile);

            exit('清理成功');
        }

        exit('无需清理');
    }
}