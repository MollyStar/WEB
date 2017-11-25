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
use Common\UserHelper;
use Kernel\Config;
use Kernel\DB;
use Kernel\Response;
use \Exception;
use Model\UserFeature;

class User
{
    public function login() {
        if (UserHelper::isLoggedAdmin()) {
            return Response::redirect('/dashboard');
        } elseif (UserHelper::isLoggedUser()) {
            return Response::redirect('/topic');
        }

        $jump = Input::get('jump') ?? '';

        return Response::view('pages.login', compact('jump'));
    }

    public function login_submit() {
        try {
            $feature = UserFeature::capture();
            $user = UserHelper::verifiedFormUser();
        } catch (Exception $e) {
            return Response::api(-1, $e->getMessage());
        }

        UserHelper::rememberIdentity($user, Input::post('keep_auth') ?? 0);

        if ($user['isgm']) {
            $jump = '/dashboard';
        } else {
            $jump = '/topic';
        }

        UserFeature::build($user['guildcard'], $feature);

        $jump = ($j = Input::post('jump')) ? $j : $jump;

        return Response::api(0, '登录成功', $jump);
    }

    public function logout() {
        UserHelper::forgetIdentity();

        return Response::redirect('/');
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
            return Response::api(-1, '验证码错误');
        }

        if (!($username = trim($username))) {
            return Response::api(-1, '请输入用户名');
        }

        if (preg_match('/[^a-zA-Z0-9]+/', $username)) {
            return Response::api(-1, '用户名只能由英文+数字组成');
        }

        $db = DB::connection();
        $db->where('username', $username);
        $result = $db->getValue('account_data', 'username');

        if ($result) {
            return Response::api(-1, '帐号已存在');
        }

        if (!$password) {
            return Response::api(-1, '请输入密码');
        }

        if (strlen($password) < 6) {
            return Response::api(-1, '密码长度过短(至少6位英文/数字)');
        }

        if ($password != $rpassword) {
            return Response::api(-1, '两次输入的密码不符，请重新确认');
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
                return Response::api(0, '注册成功');
            };
        } catch (Exception $e) {

        }

        return Response::api(-1, '注册失败');
    }

    public function ajax_search_account_by_name() {
        $key = trim(Input::post('key')) ?? '';
        $result = collect(DB::connection()->where('username', '%' . $key . '%', 'LIKE')->get('account_data', 10, [
            'username',
            'guildcard',
        ]))->map(function ($item) {
            return [$item['guildcard'], $item['username']];
        })->toArray();

        return Response::api(0, 'success', $result);
    }
}