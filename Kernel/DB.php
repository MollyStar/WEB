<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/16
 * Time: 18:30
 */

namespace Kernel;

use \MysqliDb;

class DB
{

    private static $connections = [];

    public static function connection($name = 'default') {
        return isset($connections[$name]) ? $connections[$name]::getInstance()
            : $connections[$name] = new MysqliDb (Config::get('database.' . $name));
    }
}