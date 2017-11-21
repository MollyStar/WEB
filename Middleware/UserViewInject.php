<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/21
 * Time: 上午11:04
 */

namespace Middleware;

use Kernel\View;

class UserViewInject implements MiddlewareInterface
{
    public function handle() {
        View::share('styles', '/asset/css/part/topic');
    }
}