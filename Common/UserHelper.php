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
use \Exception;

class UserHelper
{

    const USER_ADMIN = 0;

    const USER_NORMAL = 1;

    private static $currentUser;

    private static $type = null;

    public static function isLoggedAdmin() {
        return self::$type === self::USER_ADMIN;
    }

    public static function isLoggedUser() {
        return self::$type === self::USER_NORMAL;
    }

    public static function isUserLogginedGame() {
        dd(self::$currentUser);
    }

    public static function initialize() {
        if (isset($_COOKIE['AUTH_TOKEN'])) {
            $t = EncryptCookie::decrypt($_COOKIE['AUTH_TOKEN']);
            if ($t) {
                $args = explode("\t", $t);
                if (isset($args[2]) && $args[2] === Config::get('auth.admin') && isset($args[1])) {
                    $user = DB::connection()->where('guildcard', $args[1])->getOne('account_data');
                    if ($user) {
                        self::$currentUser = $user;
                        self::$type = self::USER_ADMIN;
                    }
                }
            }
        } elseif (isset($_COOKIE['AUTH_USER'])) {
            $t = EncryptCookie::decrypt($_COOKIE['AUTH_USER']);
            if ($t) {
                $args = explode("\t", $t);
                if (isset($args[2]) && $args[2] === Config::get('auth.user') && isset($args[1])) {
                    $user = DB::connection()->where('guildcard', $args[1])->getOne('account_data');
                    if ($user) {
                        self::$currentUser = $user;
                        self::$type = self::USER_NORMAL;
                    }
                }
            }
        }
    }

    public static function currentUser() {
        return self::$currentUser;
    }

    public static function verifiedFormUser() {
        $username = Input::post('username') ?? '';
        $password = Input::post('password') ?? '';
        $verify_code = Input::post('verify_code') ?? '';

        if ($verify_code != $_SESSION['phrase']) {
            throw new Exception('验证码错误');
        }

        if (!($username = trim($username))) {
            throw new Exception('请输入用户名');
        }

        if (!$password) {
            throw new Exception('请输入密码');
        }

        $user = DB::connection()->where('username', $username)->getOne('account_data', [
            'password',
            'regtime',
            'guildcard',
            'isgm',
            'isbanned',
        ]);

        if (!$user) {
            throw new Exception('无效的用户');
        }

        if ($user['isbanned']) {
            throw new Exception('用户已冻结');
        }

        $check_password = md5($password . '_' . $user['regtime'] . '_salt');

        if ($user['password'] !== $check_password) {
            throw new Exception('用户名/密码错误');
        }

        unset($user['password']);

        return $user;
    }

    public static function isOnline($guildcard) {
        $logs = ServerLogHelper::ship_logs();

        return array_key_exists($guildcard, $logs) && $logs[$guildcard]['online'];
    }

    public static function getUserInfoByGuildcard($guildcard) {
        return DB::connection()->where('guildcard', $guildcard)->getOne('account_data', [
            'password',
            'regtime',
            'guildcard',
            'isgm',
            'isbanned',
        ]);
    }
}