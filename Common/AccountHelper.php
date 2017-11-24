<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/22
 * Time: 下午6:25
 */

namespace Common;


use Codante\Binary\Binary;
use Kernel\Config;
use Kernel\DB;
use Model\Bank;
use Model\InventoryCollection;
use Model\Weapon;

class AccountHelper
{
    /**
     * @param $guildcard
     *
     * @return Bank
     */
    public static function common_bank($guildcard) {
        return Bank::make(DB::connection()->where('guildcard', $guildcard)->getValue('bank_data', 'data'));
    }

    /**
     * @param $guildcard
     *
     * @return array
     */
    public static function characters($guildcard) {
        if ($characters = DB::connection()->where('guildcard', $guildcard)->get('character_data', null, [
                'guildcard',
                'slot',
                'data',
            ]) ?? []
        ) {

            $map_sec = Config::get('server.sec');
            $map_class = Config::get('server.class');

            $characters = collect($characters)->map(function ($character) use (&$map_sec, &$map_class) {

                $data = collect(Binary::Parser(Config::get('DATA_STRUCTURE.CHARDATA'), Binary::Stream($character['data']))
                    ->data())->only(['name', 'level', 'sectionID', 'playTime', '_class'])->toArray();

                $data['level'] += 1;
                $data['sectionID'] = hexdec($data['sectionID']);
                $data['sec'] = $map_sec[$data['sectionID']];
                $data['_class'] = hexdec($data['_class']);
                $data['class'] = $map_class[$data['_class']];
                $data['name'] = CharacterHelper::decode_name($data['name']);
                $data['playTime'] = intval(ceil($data['playTime'] / 3600));

                $character['data'] = $data;

                return $character;
            })->sortBy('slot')->keyBy('slot')->toArray();
        }

        return $characters;
    }

    /**
     * 检测账号是否异常
     *
     * @param $guildcard
     *
     * @return bool
     */
    public static function is_abnormal_account($guildcard) {

        $res = collect([]);

        $common_bank = AccountHelper::common_bank($guildcard);
        $common_bankItems = collect($common_bank->items());
        if ($common_bankItems->isNotEmpty()) {
            $res = $res->merge($common_bankItems->filter(function ($item) {
                return $item->detail && $item->detail instanceof Weapon && $item->detail->abnormal === true;
            })->values());
        }


        $chardata_list = collect(DB::connection()->where('guildcard', $guildcard)->getValue('character_data', 'data'));
        if ($chardata_list->isNotEmpty()) {
            $chardata_list->each(function ($chardata) use (&$res) {
                $chardata = collect(Binary::Parser(Config::get('DATA_STRUCTURE.CHARDATA'), Binary::Stream($chardata))
                    ->data());

                $bank = Bank::make();
                $bank->fillInventory($chardata['bankInventory']);
                $bankItems = collect($bank->items());
                if ($bankItems->isNotEmpty()) {
                    $res = $res->merge($bankItems->filter(function ($item) {
                        return $item->detail && $item->detail instanceof Weapon && $item->detail->abnormal === true;
                    })->values());
                }

                $inventory = InventoryCollection::make();
                $inventory->fillInventory($chardata['inventory']);
                $inventoryItems = collect($inventory->items());
                if ($inventoryItems->isNotEmpty()) {
                    $res = $res->merge($inventoryItems->filter(function ($item) {
                        return $item->detail && $item->detail instanceof Weapon && $item->detail->abnormal === true;
                    })->values());
                }
            });
        }

        return $res->isNotEmpty();
    }
}