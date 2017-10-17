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
    public function __construct($name, $vars = []) {
        $path = VIEW_PATH . '/' . str_replace('.', '/', $name) . '.php';
        extract($vars);
        if (is_readable($path)) {
            include $path;
        }
    }

    public static function part($name, $vars = []) {
        $path = VIEW_PATH . '/' . str_replace('.', '/', $name) . '.php';
        extract($vars);
        if (is_readable($path)) {
            include $path;
        }
    }
}