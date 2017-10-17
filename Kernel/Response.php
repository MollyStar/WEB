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
    public function json($data = []) {
        header('Content-type: application/json');
        exit(json_encode($data));
    }
}