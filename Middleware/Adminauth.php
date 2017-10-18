<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/19
 * Time: 上午2:42
 */

namespace Middleware;

use Common\UserHelper;

class Adminauth implements MiddlewareInterface
{
    public function handle() {
        if (!UserHelper::isLoggedAdmin()) {
            header('HTTP/1.1 403 Forbidden');
            exit();
        }
    }
}