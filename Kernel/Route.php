<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/19
 * Time: 上午1:32
 */

namespace Kernel;

use FastRoute\RouteCollector;

class Route
{
    private static $handle;

    private static $scoopOptions = [];

    public static function register() {
        return function (RouteCollector $routeCollector) {
            self::$handle = $routeCollector;

            self::loadRoutes();
        };
    }

    private static function loadRoutes() {
        require ROOT . '/Route/web.php';
    }

    public static function getHandle() {
        return self::$handle;
    }

    public static function add($httpMethod, $route, $handler) {
        if (is_string($handler)) {
            $handler = ['controller' => $handler];
        }
        call_user_func([self::$handle, 'addRoute'], $httpMethod, $route, array_merge(self::$scoopOptions, $handler));
    }

    public static function group(callable $callback, array $options = []) {
        self::$scoopOptions = $options;
        $callback();
        self::$scoopOptions = [];
    }
}