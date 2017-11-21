<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/19
 * Time: 上午2:40
 */

return [
    'admin' => [
        \Middleware\Adminauth::class,
    ],
    'user'  => [
        \Middleware\Userauth::class,
        \Middleware\UserViewInject::class,
    ],
];