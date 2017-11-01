<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/24
 * Time: 下午11:04
 */

namespace Common;


use Kernel\DB;
use Model\CommonBank;
use Model\Item;
use Model\ItemSet;

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

        $bank = DB::connection()->where('guildcard', $guildcard)->getValue('bank_data', 'data');

        $bank_handler = $bank ? CommonBank::fromBin($bank) : CommonBank::make();
        $tobe = false;

        if ($itemOrSet instanceof Item) {
            if ($itemOrSet->isValid() && $bank_handler->remaining() > 1) {
                $bank_handler->addItem($itemOrSet);
                $tobe = true;
            }
        } elseif ($itemOrSet instanceof ItemSet) {
            $items = $itemOrSet->toCommonBankItems();
            $mst = $itemOrSet->getMST() + $bank_handler->getMST();
            if (count($items) <= $bank_handler->remaining() && $mst >= 0 && $mst < 1000000) {
                $bank_handler->setMST($mst);
                foreach ($items as $item) {
                    $bank_handler->addItem($item);
                }
                $tobe = true;
            }
        }

        if ($tobe) {
            if ($bank) {
                if (DB::connection()
                    ->where('guildcard', $guildcard)
                    ->update('bank_data', ['data' => $bank_handler->toBin()])
                ) {
                    return true;
                }
            } else {
                if (DB::connection()->insert('bank_data', [
                    'guildcard' => $guildcard,
                    'data'      => $bank_handler->toBin(),
                ])
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}