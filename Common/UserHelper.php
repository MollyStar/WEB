<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/19
 * Time: 上午3:44
 */

namespace Common;

use Kernel\Config;

class UserHelper
{

    private static $loggedIn = false;

    public static function isLoggedAdmin() {
        if (self::$loggedIn) {
            return true;
        }

        if (isset($_COOKIE['AUTH_TOKEN'])) {
            $t = EncryptCookie::decrypt($_COOKIE['AUTH_TOKEN']);
            if ($t) {
                list(, $S) = explode("\t", $t);
                if ($S === Config::get('auth.securt')) {
                    return self::$loggedIn = true;
                }
            }
        }

        return self::$loggedIn = false;
    }
}