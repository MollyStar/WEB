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
    public static function isLoggedAdmin() {
        if (isset($_COOKIE['AUTH_TOKEN'])) {
            $t = EncryptCookie::decrypt($_COOKIE['AUTH_TOKEN']);
            if ($t) {
                list(, $S) = explode("\t", $t);
                if ($S === Config::get('auth.securt')) {
                    return true;
                }
            }
        }

        return false;
    }
}