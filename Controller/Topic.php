<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/26
 * Time: 下午5:41
 */

namespace Controller;

use Carlosocarvalho\SimpleInput\Input\Input;
use Common\ItemHelper;
use Common\UserHelper;
use Kernel\DB;
use Kernel\Response;
use Model\ItemSet;
use \Exception;

class Topic
{
    public function newest_package() {
        return Response::view('pages.topic.newest_package');
    }

    public function newest_package_get() {

        // return Response::api(-1, '即将开放');

        try {
            $user = UserHelper::verifiedFormUser();
        } catch (Exception $e) {
            return Response::api(-1, $e->getMessage());
        }

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