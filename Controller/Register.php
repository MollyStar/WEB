<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/16
 * Time: 17:38
 */

namespace Controller;

use Kernel\DB;
use Kernel\View;
use Kernel\Response;

class Register
{
    public function index() {
        new View('pages.register');
    }

    public function save() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $rpassword = $_POST['rpassword'] ?? '';
        $verify_code = $_POST['verify_code'] ?? '';

        if ($verify_code != $_SESSION['phrase']) {
            Response::json(['code' => -1, 'msg' => '验证码错误']);
        }

        if (!($username = trim($username))) {
            Response::json(['code' => -1, 'msg' => '请输入用户名']);
        }

        $db = DB::connection();
        $db->where('username', $username);
        $result = $db->getValue('account_data', 'username');

        if ($result) {
            Response::json(['code' => -1, 'msg' => '帐号已存在']);
        }

        if (!$password) {
            Response::json(['code' => -1, 'msg' => '请输入密码']);
        }

        if (strlen($password) < 6) {
            Response::json(['code' => -1, 'msg' => '密码长度过短(至少6位英文/数字)']);
        }

        if ($password != $rpassword) {
            Response::json(['code' => -1, 'msg' => '两次输入的密码不符，请重新确认']);
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
            ])) {
                Response::json(['code' => 0, 'msg' => '注册成功']);
            };
        } catch (\Exception $e) {

        }

        Response::json(['code' => -1, 'msg' => '注册失败']);
    }
}