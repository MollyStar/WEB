<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 2:26
 */

namespace Kernel;

class Response
{
    private static $cacheDir = ROOT . '/__SERVER/cache';

    public static function redirect($url) {
        http_response_code(302);
        header('Location:' . $url);
    }

    public static function json($data = []) {
        header('Content-type: application/json');

        return json_encode($data);
    }

    public static function api($code, $message, $response = null) {
        return self::json(array_merge(['code' => $code, 'msg' => $message], $response ? ['response' => $response]
            : []));
    }

    public static function message($message, $options = []) {
        if (is_string($options)) {
            $options = ['url' => $options];
        }

        $options = collect([
            'code' => null,
            'url'  => 'javascript:window.history.go(-1);',
            'wait' => -1,
            'type' => 'default',
        ])->merge(['message' => $message])->merge($options)->toArray();

        return View::make('pages.message', $options);
    }

    public static function view($name, $vars = [], $cacheName = null) {
        if ($cacheName) {
            return self::saveToCache($cacheName, View::make($name, $vars));
        } else {
            return View::make($name, $vars);
        }
    }

    public static function isCached($cacheName = null) {
        return $cacheName && file_exists(self::$cacheDir . '/' . md5($cacheName) . '.cache.php');
    }

    public static function cache($cacheName = null) {
        header('Use-Cache: 1');

        return file_get_contents(self::$cacheDir . '/' . md5($cacheName) . '.cache.php');
    }

    public static function saveToCache($cacheName, $content = '') {
        file_put_contents(self::$cacheDir . '/' . md5($cacheName) . '.cache.php', $content);

        return $content;
    }
}