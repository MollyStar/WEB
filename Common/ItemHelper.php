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
use Model\CommonBankItem;
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

    public static function send_items_to_commonbank($guildcard, $items) {

        if (!$guildcard || !$user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data')) {
            return false;
        }

        $bank = DB::connection()->where('guildcard', $guildcard)->getValue('bank_data', 'data');

        $bank_handler = CommonBank::fromBin($bank);

        if ($items instanceof CommonBankItem) {
            if ($items->isValid() && $bank_handler->remaining() > 1) {
                $bank_handler->addItem($items);
                if (DB::connection()
                    ->where('guildcard', $guildcard)
                    ->update('bank_data', ['data' => $bank_handler->toBin()])
                ) {
                    return true;
                }
            }
        } elseif ($items instanceof ItemSet) {
            $items = $items->toCommonBankItems();
            if (count($items) <= $bank_handler->remaining()) {
                foreach ($items as $item) {
                    $bank_handler->addItem($item);
                }
                if (DB::connection()
                    ->where('guildcard', $guildcard)
                    ->update('bank_data', ['data' => $bank_handler->toBin()])
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}