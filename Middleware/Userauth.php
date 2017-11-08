<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/19
 * Time: 上午2:42
 */

namespace Middleware;

use Common\UserHelper;
use Kernel\Request;
use Kernel\Response;

class Userauth implements MiddlewareInterface
{
    public function handle() {
        if (!UserHelper::isLoggedAdmin() && !UserHelper::isLoggedUser()) {
            Response::redirect('/login?jump=' . urlencode(base64_encode(Request::uriWithQueryString())));
            exit();
        }
    }
}