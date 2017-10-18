<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/19
 * Time: 上午2:38
 */

namespace Kernel;


class Middleware
{
    public static function dispatch($name) {
        $handlelist = Config::get('middleware.' . $name);

        if (is_array($handlelist)) {
            array_walk($handlelist, function ($class) {
                $class::handle();
            });
        }
    }
}