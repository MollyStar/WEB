<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/16
 * Time: 21:00
 */

namespace Kernel;

class Config
{

    private static $path = ROOT . '/Config';

    private static $localCache = [];

    public static function get($key = '') {
        $key = trim($key, '.');

        if ($key) {
            if (isset(self::$localCache[$key])) {
                return self::$localCache[$key];
            } else {
                $keys = explode('.', $key);
                $file = self::$path . '/' . array_shift($keys) . '.php';
                if (is_readable($file)) {
                    $conf_content = require($file);

                    while (count($keys)) {
                        $path = array_shift($keys);
                        if (isset($conf_content[$path])) {
                            $conf_content = $conf_content[$path];
                        }
                    }

                    self::$localCache[$key] = $conf_content;

                    return $conf_content;
                }
            }
        }

        return null;
    }
}