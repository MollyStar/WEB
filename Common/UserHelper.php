<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/19
 * Time: 上午3:44
 */

namespace Common;

use Kernel\Config;
use Kernel\DB;

class UserHelper
{

    private static $loggedIn = false;

    private static $user;

    public static function isLoggedAdmin() {
        if (isset(self::$user)) {
            return true;
        }

        if (isset($_COOKIE['AUTH_TOKEN'])) {
            $t = EncryptCookie::decrypt($_COOKIE['AUTH_TOKEN']);
            if ($t) {
                list($guildcard, $S) = explode("\t", $t);
                if ($S === Config::get('auth.securt')) {
                    $user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data');
                    if ($user) {
                        self::$user = $user;

                        return true;
                    }
                }
            }
        }

        return false;
    }

    public static function currentUser() {
        return self::isLoggedAdmin() ? self::$user : null;
    }
}