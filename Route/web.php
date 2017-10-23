<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/16
 * Time: 23:01
 */

Route('GET', '/register', 'Controller\User@register');
Route('POST', '/register/save', 'Controller\User@register_save');
Route('GET', '/login', 'Controller\User@login');
Route('POST', '/login/submit', 'Controller\User@login_submit');
Route('GET', '/verifiation.jpg', 'Controller\Common@verifiation');
Route('GET', '/item_drop', 'Controller\Server\Drop@public');

RouteGroup(function () {

    Route('GET', '/item', 'Controller\Server\item@manage');
    Route('GET', '/item/import', 'Controller\Server\item@import');
    Route('POST', '/item/update', 'Controller\Server\item@update');

    Route('GET', '/drop', 'Controller\Server\Drop@manage');
    Route('POST', '/drop/update', 'Controller\Server\Drop@update');
    Route('GET', '/drop/import', 'Controller\Server\Drop@import');
    Route('GET', '/drop/clean', 'Controller\Server\Drop@clean');
    Route('GET', '/drop/export', 'Controller\Server\Drop@export');
    Route('GET', '/drop/remove_all_drop', 'Controller\Server\Drop@remove_all_drop');

    Route('GET', '/mob', 'Controller\Server\Mob@manage');
    Route('GET', '/mob/import', 'Controller\Server\Mob@import');
    Route('POST', '/mob/update', 'Controller\Server\Mob@update');
    Route('GET', '/mob/sync_simple_names', 'Controller\Server\Mob@sync_simple_names');

}, ['middleware' => 'admin']);
