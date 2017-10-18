<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/19
 * Time: 上午2:48
 */

namespace Common;

use Kernel\Config;

class EncryptCookie
{

    public static function encrypt($value) {
        $key = Config::get('auth.key');
        $iv = substr($key, 0, 8) . substr($key, -8);

        return base64_encode(openssl_encrypt($value, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }

    public static function decrypt($payload) {
        $key = Config::get('auth.key');
        $iv = substr($key, 0, 8) . substr($key, -8);

        return (openssl_decrypt(base64_decode($payload), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }
}