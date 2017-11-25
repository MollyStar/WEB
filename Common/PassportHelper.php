<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/25
 * Time: 上午2:47
 */

namespace Common;


use Kernel\DB;

class PassportHelper
{
    /**
     * 获取或者创建passid
     *
     * @param $current_user
     *
     * @return int|null
     */
    public static function getOrCreatePassid(&$current_user) {
        if (!$current_user) {
            $current_user = UserHelper::currentUser();
        }

        if ($current_user['passid'] > 0) {
            $passid = $current_user['passid'];
        } else {
            DB::connection()->startTransaction();
            try {
                $passid = DB::connection()->insert('passport', [
                    'is_abnormal' => AccountHelper::is_abnormal_account($current_user['guildcard']) ? 1 : 0,
                ]);
                DB::connection()->insert('passport_account_relation', [
                    'passid'    => $passid,
                    'guildcard' => $current_user['guildcard'],
                ]);
                DB::connection()->commit();
                $current_user['passid'] = $passid;
            } catch (\Exception $e) {
                DB::connection()->rollback();
                $passid = null;
            }
        }

        return $passid;
    }

    public static function bindAccount(&$current_user, $guildcard) {
        if (!$current_user) {
            $current_user = UserHelper::currentUser();
        }
        if ($current_user && $passid = self::getOrCreatePassid($current_user)) {
            DB::connection()->startTransaction();
            try {
                if ($pass_info = UserHelper::getPassport($guildcard)) {
                    // 钱全部转移到当前passport
                    DB::connection()->where('passid', $passid)->update('passport', [
                        'currency'    => intval($current_user['currency']) + intval($pass_info['currency']),
                        // 问题账号污染
                        'is_abnormal' => intval($current_user['is_abnormal']) | intval($pass_info['is_abnormal']),
                    ]);
                    // 所有相关帐号重新绑定
                    DB::connection()->where('passid', $pass_info['passid'])->update('passport_account_relation', [
                        'passid' => $passid,
                    ]);
                    // 删掉原主passport
                    DB::connection()->where('passid', $pass_info['passid'])->delete('passport');
                } else {
                    // 直接绑定
                    DB::connection()->insert('passport_account_relation', [
                        'passid'    => $passid,
                        'guildcard' => $guildcard,
                    ]);
                    if ($is_abnormal = AccountHelper::is_abnormal_account($guildcard)) {
                        DB::connection()->where('passid', $passid)->update('passport', [
                            'is_abnormal' => 1, // 问题账号污染
                        ]);
                    }
                }
                DB::connection()->commit();

                return true;

            } catch (\Exception $e) {
                DB::connection()->rollback();
            }
        }

        return false;
    }
}