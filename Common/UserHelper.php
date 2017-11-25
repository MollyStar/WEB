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

    public static function isLogged() {
        return !is_null(self::$type);
    }

    public static function isLoggedAdmin() {
        return self::$type === self::USER_ADMIN;
    }

    public static function isLoggedUser() {
        return self::$type === self::USER_NORMAL;
    }

    public static function isUserLogginedGame() {
        return self::$currentUser['lastip'] && self::$currentUser['lasthwinfo'];
    }

    public static function getPassport($guildcard) {
        return DB::connection()
            ->join('passport p', 'p.passid=par.passid')
            ->where('par.guildcard', $guildcard)
            ->getOne('passport_account_relation par', 'p.*');
    }

    public static function getPassportRelation($passid) {
        return DB::connection()
                   ->join('account_data a', 'a.guildcard=par.guildcard', 'LEFT')
                   ->where('par.passid', $passid)
                   ->get('passport_account_relation par', null, ['a.guildcard', 'a.username']) ?? [];
    }

    public static function mergePassportInfo(&$user) {
        if ($pass_info = self::getPassport($user['guildcard'])) {
            $user['currency'] = intval($pass_info['currency']);
            $user['is_abnormal'] = intval($pass_info['is_abnormal']);
            $user['passid'] = $pass_info['passid'];
            $user['pass_accounts'] = collect(self::getPassportRelation($pass_info['passid']))
                ->keyBy('guildcard')
                ->forget($user['guildcard'])
                ->toArray();
        } else {
            $user['currency'] = $user['pass_accounts'] = $user['passid'] = null;
        }
    }

    public static function initialize() {
        if (($guildcard = self::getAuth()) &&
            ($user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data'))
        ) {
            self::mergePassportInfo($user);
            self::$currentUser = $user;
            self::$type = $user['isgm'] == 1 ? self::USER_ADMIN : self::USER_NORMAL;
        }
    }

    private static function getAuth() {
        switch (true) {
            case isset($_SESSION['adminid']):
                return $_SESSION['adminid'];
            case isset($_COOKIE['AUTH_TOKEN']):
                if ($t = EncryptCookie::decrypt($_COOKIE['AUTH_TOKEN'])) {
                    $args = explode("\t", $t);
                    if (isset($args[2]) && $args[2] === Config::get('auth.admin') && isset($args[1])) {
                        return $_SESSION['adminid'] = $args[1];
                    }
                }
                break;
            case isset($_SESSION['userid']):
                return $_SESSION['userid'];
            case isset($_COOKIE['AUTH_USER']):
                if ($t = EncryptCookie::decrypt($_COOKIE['AUTH_USER'])) {
                    $args = explode("\t", $t);
                    if (isset($args[2]) && $args[2] === Config::get('auth.user') && isset($args[1])) {
                        return $_SESSION['userid'] = $args[1];
                    }
                }
                break;
        }

        return null;
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
            'username',
            'password',
            'regtime',
            'guildcard',
            'isgm',
            'isbanned',
        ]);
    }

    /**
     * 记住用户身份
     *
     * @param      $user
     * @param bool $keep_auth
     */
    public static function rememberIdentity($user, $keep_auth = false) {
        if ($user['isgm']) {
            $_SESSION['adminid'] = $user['guildcard'];
            $keep_auth &&
            setcookie('AUTH_TOKEN', EncryptCookie::encrypt(time() .
                                                           "\t" .
                                                           $user['guildcard'] .
                                                           "\t" .
                                                           Config::get('auth.admin')), time() +
                                                                                       86400, '/', null, null, true);
        } else {
            $_SESSION['userid'] = $user['guildcard'];
            $keep_auth &&
            setcookie('AUTH_USER', EncryptCookie::encrypt(time() .
                                                          "\t" .
                                                          $user['guildcard'] .
                                                          "\t" .
                                                          Config::get('auth.user')), time() +
                                                                                     86400, '/', null, null, true);
        }
    }

    /**
     * 移除已经记录的身份
     */
    public static function forgetIdentity() {
        if (UserHelper::isLoggedAdmin()) {
            $_SESSION['adminid'] = null;
            setcookie('AUTH_TOKEN', '', 0, '/', null, null, true);
        } elseif (UserHelper::isLoggedUser()) {
            $_SESSION['userid'] = null;
            setcookie('AUTH_USER', '', 0, '/', null, null, true);
        }
    }
}