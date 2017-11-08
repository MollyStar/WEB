<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/16
 * Time: 18:09
 */

namespace Kernel;

use \Exception;

class View
{

    public static $vars = [];

    public static function make($name, $vars = []) {
        $path = VIEW_PATH . '/' . str_replace('.', '/', $name) . '.php';
        if (is_readable($path)) {
            self::$vars = $vars;
            extract($vars);
            ob_start();
            include $path;
            $contents = ob_get_contents();
            ob_end_clean();

            return $contents;
        } else {
            throw new Exception(sprintf('View templete "%s" not exists!', $name));
        }
    }

    public static function part($name, $vars = []) {
        $path = VIEW_PATH . '/' . str_replace('.', '/', $name) . '.php';
        if (is_readable($path)) {
            extract(self::$vars);
            extract($vars);
            include $path;
        } else {
            throw new Exception(sprintf('View templete part "%s" not exists!', $name));
        }
    }
}