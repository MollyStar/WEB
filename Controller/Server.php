<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 下午10:42
 */

namespace Controller;

use Kernel\Config;
use Kernel\DB;

class Server
{
    public function drop() {

    }

    public function clean() {
        $lockFile = ROOT . '/__SERVER/drop/imprted.lock';
        if (file_exists($lockFile)) {
            DB::connection()->rawQuery('truncate table `item_drop`');
            @unlink($lockFile);

            exit('清理成功');
        }

        exit('无需清理');
    }

    public function import() {
        $path = ROOT . '/__SERVER/drop';

        if (file_exists($path . '/imprted.lock')) {
            echo '已导入，若要重新导入请先<a href="/drop/clean" target="_blank">清空</a>';

            return false;
        }

        file_put_contents($path . '/imprted.lock', '');

        $config = Config::get('drop');

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
                                $name = $tk == 1 ? substr($item[0], 2) : 'box';
                                $lv = $tk == 1 ? null : $item[0];
                                $data[] = [
                                    'hash'  => md5($epk . $dk . $sk . $tk . $order . $name),
                                    'ep'    => $epk,
                                    'dif'   => $dk,
                                    'sec'   => $sk,
                                    'type'  => $tk,
                                    'order' => $order,
                                    'name'  => $name,
                                    'lv'    => $lv,
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
}