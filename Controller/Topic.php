<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/26
 * Time: 下午5:41
 */

namespace Controller;

use Carlosocarvalho\SimpleInput\Input\Input;
use Codante\Binary\Binary;
use Common\AccountHelper;
use Common\CharacterHelper;
use Common\ItemHelper;
use Common\UserHelper;
use Kernel\Config;
use Kernel\DB;
use Kernel\Response;
use Kernel\View;
use Model\ItemSet;
use \Exception;

class Topic
{
    public function index() {

        $user = UserHelper::currentUser();

        $characters = AccountHelper::characters($user['guildcard']);
        $commonbank = AccountHelper::common_bank($user['guildcard']);

        $currency_codes = ['031000', '031001', '031002', '031003'];
        $currency_items = $commonbank->filter('hex', $currency_codes);
        $map_items = collect(DB::connection()->where('hex', $currency_codes, 'IN')->get('map_items'))
            ->keyBy('hex')
            ->toArray();

        $currency = collect($currency_items)->map(function ($item) use ($map_items) {
            return ['name' => $map_items[$item->hex]['name_zh'], 'num' => $item->num];
        })->toArray();

//        dp($currency, $characters);

        return Response::view('pages.topic.index', compact('characters', 'currency', 'map_items'));
    }

    public function notice() {
        $item_changes = file_get_contents(ROOT . '/__SERVER/item/change.md');

        return Response::view('pages.notice', compact('item_changes'));
    }

    public function newest_package() {
        $user = UserHelper::currentUser();
        if (!$user['isgm'] &&
            DB::connection()
                ->where('guildcard', $user['guildcard'])
                ->where('name', 'NEWEST_PACKAGE')
                ->getOne('topic_record')
        ) {
            return Response::message('您已经领取过了');
        }

        return Response::view('pages.topic.newest_package');
    }

    public function newest_package_get() {

        $user = UserHelper::currentUser();

        if (UserHelper::isOnline($user['guildcard'])) {
            return Response::api(-1, '请登出您的游戏帐号再尝试领取');
        }

        $types = ['HU', 'RA', 'FO'];
        $type = Input::post('sec');
        $type = 'NEWEST_PACKAGE_' . $types[$type];


        if (!$user['isgm'] &&
            DB::connection()
                ->where('guildcard', $user['guildcard'])
                ->where('name', 'NEWEST_PACKAGE')
                ->getOne('topic_record')
        ) {
            return Response::api(-1, '您已经领取过了');
        } else {
            try {
                if (ItemHelper::send_items_to_commonbank($user['guildcard'], ItemSet::make($type))) {
                    !$user['isgm'] && DB::connection()->insert('topic_record', [
                        'guildcard' => $user['guildcard'],
                        'name'      => 'NEWEST_PACKAGE',
                    ]);

                    return Response::api(0, '领取成功');
                }
            } catch (Exception $e) {
                return Response::api(-1, '领取失败，' . $e->getMessage());
            }
        }

        return Response::api(-1, '领取失败，请稍后重试');
    }
}