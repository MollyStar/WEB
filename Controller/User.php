<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/19
 * Time: 上午2:54
 */

namespace Controller;

use Carlosocarvalho\SimpleInput\Input\Input;
use Common\EncryptCookie;
use Kernel\Config;
use Kernel\DB;
use Kernel\Response;

class User
{
    public function login() {
        return Response::view('pages.login');
    }

    public function login_submit() {
        $username = Input::post('username') ?? '';
        $password = Input::post('password') ?? '';
        $verify_code = Input::post('verify_code') ?? '';

        if ($verify_code != $_SESSION['phrase']) {
            return Response::api(-1,'验证码错误');
        }

        if (!($username = trim($username))) {
            return Response::api(-1,'请输入用户名');
        }

        if (!$password) {
            return Response::api(-1,'请输入密码');
        }

        $user = DB::connection()->where('username', $username)->getOne('account_data', [
            'password',
            'regtime',
            'guildcard',
        ]);

        $check_password = md5($password . '_' . $user['regtime'] . '_salt');

        if (!$user || $user['password'] !== $check_password) {
            return Response::api(-1,'用户名/密码错误');
        }

        setcookie('AUTH_TOKEN', EncryptCookie::encrypt($user['guildcard'] . "\t" . Config::get('auth.securt')), time() +
                                                                                                                86400, '/', null, null, true);

        return Response::api(0,'登录成功');
    }

    public function register() {
        return Response::view('pages.register');
    }

    public function register_save() {
        $username = Input::post('username') ?? '';
        $password = Input::post('password') ?? '';
        $rpassword = Input::post('rpassword') ?? '';
        $verify_code = Input::post('verify_code') ?? '';

        if ($verify_code != $_SESSION['phrase']) {
            return Response::api(-1,'验证码错误');
        }

        if (!($username = trim($username))) {
            return Response::api(-1,'请输入用户名');
        }

        $db = DB::connection();
        $db->where('username', $username);
        $result = $db->getValue('account_data', 'username');

        if ($result) {
            return Response::api(-1,'帐号已存在');
        }

        if (!$password) {
            return Response::api(-1,'请输入密码');
        }

        if (strlen($password) < 6) {
            return Response::api(-1,'密码长度过短(至少6位英文/数字)');
        }

        if ($password != $rpassword) {
            return Response::api(-1,'两次输入的密码不符，请重新确认');
        }

        $regtime = ceil(time() / 3600);
        $password = md5($password . '_' . $regtime . '_salt');

        $email = $username . '@kro_psobb.com';

        try {
            if ($db->insert('account_data', [
                'username' => $username,
                'password' => $password,
                'email'    => $email,
                'regtime'  => $regtime,
                'isactive' => 1,
            ])
            ) {
                return Response::api(0,'注册成功');
            };
        } catch (\Exception $e) {

        }

        return Response::api(-1,'注册失败');
    }
}