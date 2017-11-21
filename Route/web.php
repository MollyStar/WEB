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
Route('GET', '/notice', 'Controller\Topic@notice');

// 支付
Route('GET', '/trade/initiate', 'Controller\Trade@initiate');
Route('GET', '/trade/notify', 'Controller\Trade@notify');
Route('GET', '/trade/return', 'Controller\Trade@return');


RouteGroup(function () {
    Route('GET', '/topic', 'Controller\Topic@index');
    // 活动
    Route('GET', '/topic/newest_package', 'Controller\Topic@newest_package');
    Route('POST', '/topic/newest_package/get', 'Controller\Topic@newest_package_get');
}, ['middleware' => 'user']);

RouteGroup(function () {
    Route('GET', '/dashboard', 'Controller\Dashboard@index');
    Route('GET', '/test', 'Controller\Dashboard@test');

    //    Route('GET', '/server/process/login_server', 'Controller\Server\Process@login_server');
    //    Route('GET', '/server/process/patch_server', 'Controller\Server\Process@patch_server');

    // 物品
    Route('GET', '/item', 'Controller\Server\Item@manage');
    // Route('GET', '/item/import', 'Controller\Server\item@import');
    Route('POST', '/item/update', 'Controller\Server\Item@update');
    Route('GET', '/item/stat_boosts', 'Controller\Server\Item@stat_boosts');
    Route('GET', '/item/tech_boosts', 'Controller\Server\Item@tech_boosts');


    // 套装
    Route('GET', '/item_set', 'Controller\Server\ItemSet@list');
    Route('GET', '/item_set/detail', 'Controller\Server\ItemSet@detail');
    Route('POST', '/item_set/detail/save', 'Controller\Server\ItemSet@save');
    Route('POST', '/item_set/detail/delete', 'Controller\Server\ItemSet@delete');
    Route('GET', '/item_set/send', 'Controller\Server\ItemSet@send');
    Route('POST', '/item_set/send_to_account_commonbank', 'Controller\Server\ItemSet@send_to_account_commonbank');


    // 掉落
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

    Route('GET', '/account', 'Controller\Server\Account@manage');
    Route('GET', '/account/common_bank', 'Controller\Server\Account@common_bank');
    Route('POST', '/account/common_bank/save', 'Controller\Server\Account@common_bank_save');
    Route('GET', '/account/character', 'Controller\Server\Account@character');
    Route('GET', '/account/character/detail', 'Controller\Server\Account@character_detail');

    // 用户
    Route('POST', '/user/ajax_search_account_by_name', 'Controller\User@ajax_search_account_by_name');


    Route('GET', '/tools/db', 'Controller\Tools\DBStructure@index');
    Route('GET', '/tools/server/status', 'Controller\Tools\Server@status');

}, ['middleware' => 'admin']);
