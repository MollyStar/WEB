<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/16
 * Time: 18:09
 */

namespace Kernel;

class View
{

    public static $vars = [];

    public static function make($name, $vars = []) {
        ob_start();
        $path = VIEW_PATH . '/' . str_replace('.', '/', $name) . '.php';
        self::$vars = $vars;
        extract($vars);
        if (is_readable($path)) {
            include $path;
        }
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    public static function part($name, $vars = []) {
        $path = VIEW_PATH . '/' . str_replace('.', '/', $name) . '.php';
        extract(self::$vars);
        extract($vars);
        if (is_readable($path)) {
            include $path;
        }
    }
}