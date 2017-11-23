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

        $pass_accounts = $user['pass_accounts'] ?? [];

        //        dp($currency, $characters);

        return Response::view('pages.topic.index', compact('characters', 'currency', 'map_items', 'pass_accounts'));
    }

    public function bind_passport() {
        return Response::view('pages.topic.bind_passport');
    }

    public function bind_passport_submit() {
        try {
            $user = UserHelper::verifiedFormUser();
        } catch (Exception $e) {
            return Response::api(-1, $e->getMessage());
        }

        $current_user = UserHelper::currentUser();

        if ($user['guildcard'] === $current_user['guildcard']) {
            return Response::api(-1, '不可以绑定自己');
        }

        if (!$current_user['passid']) {
            DB::connection()->startTransaction();
            try {
                $passid = DB::connection()->insert('passport', []);
                DB::connection()->insert('passport_account_relation', [
                    'passid'    => $passid,
                    'guildcard' => $current_user['guildcard'],
                ]);
                DB::connection()->commit();
            } catch (\Exception $e) {
                DB::connection()->rollback();

                return Response::api(-1, $e->getMessage());
            }
        } else {
            $passid = $current_user['passid'];
        }

        DB::connection()->startTransaction();
        try {
            if ($pass_info = UserHelper::getPassport($user['guildcard'])) {
                // 钱全部转移到当前passport
                DB::connection()->where('passid', $passid)->update('passport', [
                    'currency' => $current_user['currency'] + $pass_info['currency'],
                ]);
                // 所有相关帐号重新绑定
                DB::connection()->where('passid', $pass_info['passid'])->update('passport_account_relation', [
                    'passid' => $passid,
                ]);
                // 删掉原主passport
                DB::connection()->where('passid', $pass_info['passid'])->delete('passport');
            } else {
                DB::connection()->insert('passport_account_relation', [
                    'passid'    => $passid,
                    'guildcard' => $user['guildcard'],
                ]);
            }
            DB::connection()->commit();

            return Response::api(0, '绑定成功', '/topic');

        } catch (\Exception $e) {
            DB::connection()->rollback();

            return Response::api(-1, $e->getMessage());
        }

        return Response::api(-1, '绑定中出现问题，请稍后重试');
    }

    public function switch_account() {
        $guildcard = Input::get('guildcard');
        if ($guildcard > 0 &&
            ($pass_info = UserHelper::getPassport($guildcard)) &&
            $pass_info['passid'] === UserHelper::currentUser()['passid']
        ) {
            // 验证通过，可以切换
            if ($user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data', [
                'username',
                'guildcard',
                'isgm',
            ])
            ) {
                UserHelper::forget_identity();
                UserHelper::remember_identity($user, Input::post('keep_auth') ?? 0);

                return Response::message('您现在将以 ' . $user['username'] . ' 身份登录', ['url' => '/topic']);
            }
        }
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