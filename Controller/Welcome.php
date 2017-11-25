<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/8
 * Time: 下午7:08
 */

namespace Controller;

use Kernel\Response;

class Welcome
{
    public function index() {
        return Response::view('pages.welcome');
    }
}