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

RouteGroup(function () {
    Route('GET', '/items', 'Controller\Server@items');
    Route('GET', '/items/import', 'Controller\Server@items_import');

    Route('GET', '/drop', 'Controller\Server@drop');
    Route('GET', '/drop/import', 'Controller\Server@drop_import');
    Route('GET', '/drop/clean', 'Controller\Server@drop_clean');
    Route('GET', '/drop/export', 'Controller\Server@drop_export');

    Route('GET', '/mob', 'Controller\Server@mob');
    Route('GET', '/mob/import', 'Controller\Server@mob_import');
    Route('POST', '/mob/update', 'Controller\Server@mob_update');
    Route('GET', '/mob/sync_simple_names', 'Controller\Server@mob_sync_simple_names');

}, ['middleware' => 'admin']);
