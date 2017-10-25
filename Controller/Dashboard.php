<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 1:12
 */

namespace Controller;

use Kernel\Response;

class Dashboard
{
    public function index() {
        return Response::view('pages.dashboard');
    }
}