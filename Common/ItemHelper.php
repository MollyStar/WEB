<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/24
 * Time: 下午11:04
 */

namespace Common;


use Kernel\DB;
use Model\Bank;
use Model\BankItem;
use Model\ItemSet;
use \Exception;

class ItemHelper
{
    private static $cache = [];

    public static function all_items() {
        return self::$cache['items']
               ??
               self::$cache['items'] = DB::connection()->orderBy('hex', 'asc')->get('map_items', null, [
                   'hex',
                   'name',
                   'name_zh',
               ]);
    }

    public static function map_items() {
        return collect(self::all_items())->keyBy('hex')->toArray();
    }

    public static function send_items_to_commonbank($guildcard, $itemOrSet) {

        if (!$guildcard || !$user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data')) {
            return false;
        }

        $bin = DB::connection()->where('guildcard', $guildcard)->getValue('bank_data', 'data');

        $bank = Bank::make($bin);
        $tobe = false;

        if ($itemOrSet instanceof BankItem) {
            if ($bank->remaining() < 1) {
                throw new Exception('公共仓库没有足够的位置');
            }

            if ($itemOrSet->isValid()) {
                $bank->addItem($itemOrSet);
                $tobe = true;
            }
        } elseif ($itemOrSet instanceof ItemSet) {
            $items = $itemOrSet->toBankItems();
            $mst = $itemOrSet->getMST() + $bank->getMST();

            if (count($items) > $bank->remaining()) {
                throw new Exception('公共仓库没有足够的位置');
            }

            if ($mst < 0 || $mst > 999999) {
                throw new Exception('公共银行无法装下更多的美塞塔');
            }

            $bank->setMST($mst);
            foreach ($items as $item) {
                $bank->addItem($item);
            }
            $tobe = true;
        }

        if ($tobe) {
            if ($bank) {
                if (DB::connection()->where('guildcard', $guildcard)->update('bank_data', ['data' => $bank->toBin()])) {
                    return true;
                }
            } else {
                if (DB::connection()->insert('bank_data', [
                    'guildcard' => $guildcard,
                    'data'      => $bank->toBin(),
                ])
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}