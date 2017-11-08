<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 2:26
 */

namespace Kernel;

class Request
{
    private static $headers;

    private static $server;

    private static function getHeaders() {
        return self::$headers ?? self::$headers = getallheaders();
    }

    public static function isAjax() {
        $all_headers = self::getHeaders();

        return isset($all_headers['X-Requested-With']) && $all_headers['X-Requested-With'] === 'XMLHttpRequest';
    }

    public static function getServer() {
        return self::$server ?? self::$server = $_SERVER;
    }

    public static function host() {
        return self::getServer()['SERVER_NAME'];
    }

    public static function protocol() {
        return self::getServer()['REQUEST_SCHEME'];
    }

    public static function basePath() {
        return self::protocol() . '//' . self::host();
    }

    public static function uri() {
        // Strip query string (?foo=bar) and decode URI
        $uri = self::getServer()['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        return $uri;
    }

    public static function uriWithQueryString() {
        return self::getServer()['REQUEST_URI'];
    }

    public static function fullUri() {
        return self::basePath() . self::uriWithQueryString();
    }

    public static function method() {
        return self::getServer()['REQUEST_METHOD'];
    }
}