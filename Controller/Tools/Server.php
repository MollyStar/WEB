<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/26
 * Time: 上午10:12
 */

namespace Controller\Tools;


use Kernel\Config;
use Kernel\Response;

class Server
{
    public function status() {
        $portsConfig = Config::get('server.ports');
        ob_start();
        passthru('netstat -ano');
        $ret = ob_get_contents();
        ob_end_clean();
        $arr = preg_split('/\r\n/', $ret);
        $ports = collect(array_slice($arr, 4))->map(function ($item) use ($portsConfig) {
            if (preg_match('/\:(5[\d]78|1[12]000)/', $item, $matchs)) {
                return $matchs[1];
            }

            return '';
        })->filter()->toArray();

        $status = collect($portsConfig)->map(function ($item, $port) use ($ports) {
            $item[] = in_array($port, $ports) ? true : false;

            return $item;
        })->toArray();

        return Response::api(0, 'status', $status);
    }
}