<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/26
 * Time: 下午5:41
 */

namespace Controller;


use Carlosocarvalho\SimpleInput\Input\Input;
use Kernel\Response;

class Topic
{
    public function newest_package() {
        return Response::view('pages.topic.newest_package');
    }

    public function newest_package_get() {

        Input::post('username');
        Input::post('password');

        //dd(ItemHelper::send_items_to_commonbank(1, ItemSet::make('NEWEST_PACKAGE_HU')));
    }
}