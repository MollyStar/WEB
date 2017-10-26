<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/26
 * Time: 下午5:41
 */

namespace Controller;


use Kernel\Response;

class Topic
{
    public function newest_package() {
        return Response::view('pages.topic.newest_package');
    }
}