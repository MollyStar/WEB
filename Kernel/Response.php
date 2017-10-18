<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 2:26
 */

namespace Kernel;

class Response
{
    public static function json($data = []) {
        header('Content-type: application/json');

        return json_encode($data);
    }

    public static function view($name, $vars = []) {
        return View::make($name, $vars);
    }
}