<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/10
 * Time: 下午12:31
 */

namespace Controller\Server;


use Kernel\Config;

class Process
{
    public function login_server() {
        $exec = realpath(Config::get('server.root') . '/LOGIN/login_server.exe');
        if (is_executable($exec)) {
            $WshShell = new \COM("WScript.Shell");
            $oExec = $WshShell->Run($exec, 0, false);

            // pclose(popen('START ' . $exec, "rb"));
        }
    }

    public function patch_server() {
        $exec = realpath(Config::get('server.root') . '/LOGIN/patch_server.exe');
        if (is_executable($exec)) {
            popen('start /b ' . $exec, 'rb');
        }
    }
}