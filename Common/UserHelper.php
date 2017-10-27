<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/19
 * Time: 上午3:44
 */

namespace Common;

use Carlosocarvalho\SimpleInput\Input\Input;
use Kernel\Config;
use Kernel\DB;

class UserHelper
{
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

    public static function verifiedFormUser() {
        $username = Input::post('username') ?? '';
        $password = Input::post('password') ?? '';
        $verify_code = Input::post('verify_code') ?? '';

        if ($verify_code != $_SESSION['phrase']) {
            throw new \Exception('验证码错误');
        }

        if (!($username = trim($username))) {
            throw new \Exception('请输入用户名');
        }

        if (!$password) {
            throw new \Exception('请输入密码');
        }

        $user = DB::connection()->where('username', $username)->getOne('account_data', [
            'password',
            'regtime',
            'guildcard',
            'isgm',
            'isbanned',
        ]);

        if (!$user) {
            throw new \Exception('无效的用户');
        }

        if ($user['isbanned']) {
            throw new \Exception('用户已冻结');
        }

        $check_password = md5($password . '_' . $user['regtime'] . '_salt');

        if ($user['password'] !== $check_password) {
            throw new \Exception('用户名/密码错误');
        }

        unset($user['password']);

        return $user;
    }

    public static function isOnline($guildcard) {
        $logs = ServerLogHelper::ship_logs();

        return array_key_exists($guildcard, $logs) && $logs[$guildcard]['online'];
    }
}