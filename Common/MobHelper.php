<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/18
 * Time: 下午11:39
 */

namespace Common;


use Kernel\Config;

class MobHelper
{
    public static function getNameZH($name) {

        $mobLang = Config::get('lang.mob');

        $names = explode('/', $name);
        foreach ($names as $k => $name) {
            $names[$k] = $mobLang[$name] ?? '??';
        }

        return join('/', $names);
    }
}