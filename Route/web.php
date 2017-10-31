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
Route('GET', '/logout', 'Controller\User@logout');
Route('GET', '/verifiation.jpg', 'Controller\Common@verifiation');
Route('GET', '/item_drop', 'Controller\Server\Drop@public');

// 活动
Route('GET', '/topic/newest_package', 'Controller\Topic@newest_package');
Route('POST', '/topic/newest_package/get', 'Controller\Topic@newest_package_get');

RouteGroup(function () {
    Route('GET', '/dashboard', 'Controller\Dashboard@index');
    // Route('GET', '/test', 'Controller\Dashboard@test');

    Route('GET', '/item', 'Controller\Server\item@manage');
    // Route('GET', '/item/import', 'Controller\Server\item@import');
    Route('POST', '/item/update', 'Controller\Server\item@update');

    // 套装
    Route('GET', '/item_set', 'Controller\Server\item@item_set');
    Route('GET', '/item_set/detail', 'Controller\Server\item@item_set_detail');
    Route('POST', '/item_set/detail/save', 'Controller\Server\item@item_set_detail_save');
    Route('POST', '/item_set/detail/delete', 'Controller\Server\item@item_set_detail_delete');

    Route('GET', '/drop', 'Controller\Server\Drop@manage');
    Route('POST', '/drop/update', 'Controller\Server\Drop@update');
    Route('POST', '/drop/box_delete', 'Controller\Server\Drop@box_delete');
    Route('GET', '/drop/import', 'Controller\Server\Drop@import');
    Route('GET', '/drop/clean', 'Controller\Server\Drop@clean');
    Route('GET', '/drop/export', 'Controller\Server\Drop@export');
    Route('GET', '/drop/remove_all_drop', 'Controller\Server\Drop@remove_all_drop');

    Route('GET', '/mob', 'Controller\Server\Mob@manage');
    Route('GET', '/mob/import', 'Controller\Server\Mob@import');
    Route('POST', '/mob/update', 'Controller\Server\Mob@update');
    Route('GET', '/mob/sync_simple_names', 'Controller\Server\Mob@sync_simple_names');

    Route('GET', '/character', 'Controller\Server\Character@manage');
    Route('GET', '/character/bank', 'Controller\Server\Character@bank');
    Route('POST', '/character/bank/save', 'Controller\Server\Character@bank_save');

    Route('GET', '/tools/db', 'Controller\Tools\DBStructure@index');
    Route('GET', '/tools/server/status', 'Controller\Tools\Server@status');

}, ['middleware' => 'admin']);
