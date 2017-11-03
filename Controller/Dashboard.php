<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 1:12
 */

namespace Controller;

use Carlosocarvalho\SimpleInput\Input\Input;
use Codante\Binary\Binary;
use Kernel\Config;
use Kernel\DB;
use Kernel\Response;

class Dashboard
{
    public function index() {
        return Response::view('pages.dashboard');
    }

    public function test() {

        dd(Config::get('DATA_STRUCTURE.CHARDATA'));

        $guildcard = Input::get('guildcard');
        if ($guildcard > 0) {

            $user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data');

            if ($user) {

                $data = DB::connection()->where('guildcard', $guildcard)->getValue('character_data', 'data');

                $parser = Binary::Parser([
                    'packetSize'            => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'command'               => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'flags'                 => Binary::UNSIGNED_CHAR(4, Binary::RAW_FILTER_HEX),
                    'inventoryUse'          => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'HPuse'                 => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'TPuse'                 => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'lang'                  => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'inventory'             => Binary::COLLECTION([
                        'in_use' => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                        'flag'   => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                        'item'   => Binary::COLLECTION([
                            'data'   => Binary::UNSIGNED_CHAR(12, Binary::RAW_FILTER_HEX),
                            'itemid' => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                            'data2'  => Binary::UNSIGNED_CHAR(4, Binary::RAW_FILTER_HEX),
                        ]),
                    ], 30),
                    'ATP'                   => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'MST'                   => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'EVP'                   => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'HP'                    => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'DFP'                   => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'TP'                    => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'LCK'                   => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'ATA'                   => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'unknown'               => Binary::UNSIGNED_CHAR(8, Binary::RAW_FILTER_HEX),
                    'level'                 => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'unknown2'              => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'XP'                    => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'meseta'                => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'gcString'              => Binary::SIGNED_CHAR(10, Binary::RAW_FILTER_HEX),
                    'unknown3'              => Binary::UNSIGNED_CHAR(14, Binary::RAW_FILTER_HEX),
                    'nameColorBlue'         => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'nameColorGreen'        => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'nameColorRed'          => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'nameColorTransparency' => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'skinID'                => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'unknown4'              => Binary::UNSIGNED_CHAR(18, Binary::RAW_FILTER_HEX),
                    'sectionID'             => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    '_class'                => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'skinFlag'              => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'unknown5'              => Binary::UNSIGNED_CHAR(5, Binary::RAW_FILTER_HEX),
                    'costume'               => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'skin'                  => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'face'                  => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'head'                  => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'hair'                  => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'hairColorRed'          => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'hairColorBlue'         => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'hairColorGreen'        => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'proportionX'           => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'proportionY'           => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'name'                  => Binary::UNSIGNED_CHAR(24, Binary::RAW_FILTER_HEX),
                    'playTime'              => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'unknown6'              => Binary::UNSIGNED_CHAR(4, Binary::RAW_FILTER_HEX),
                    'keyConfig'             => Binary::UNSIGNED_CHAR(232, Binary::RAW_FILTER_HEX),
                    'techniques'            => Binary::UNSIGNED_CHAR(20, Binary::RAW_FILTER_HEX),
                    'unknown7'              => Binary::UNSIGNED_CHAR(16, Binary::RAW_FILTER_HEX),
                    'options'               => Binary::UNSIGNED_CHAR(4, Binary::RAW_FILTER_HEX),
                    'unknown8'              => Binary::UNSIGNED_CHAR(520, Binary::RAW_FILTER_HEX),
                    'bankUse'               => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'bankMeseta'            => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'bankInventory'         => Binary::COLLECTION([
                        'data'   => Binary::UNSIGNED_CHAR(12, Binary::RAW_FILTER_HEX),
                        'itemid' => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                        'mag'    => Binary::UNSIGNED_INTEGER(4, Binary::RAW_FILTER_HEX),
                        'num'    => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    ], 200),
                    'guildCard'             => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'name2'                 => Binary::UNSIGNED_CHAR(24, Binary::RAW_FILTER_HEX),
                    'unknown9'              => Binary::UNSIGNED_CHAR(232, Binary::RAW_FILTER_HEX),
                    'reserved1'             => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'reserved2'             => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'sectionID2'            => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    '_class2'               => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
                    'unknown10'             => Binary::UNSIGNED_CHAR(4, Binary::RAW_FILTER_HEX),
                    'symbol_chats'          => Binary::UNSIGNED_CHAR(1248, Binary::RAW_FILTER_HEX),
                    'shortcuts'             => Binary::UNSIGNED_CHAR(2624, Binary::RAW_FILTER_HEX),
                    'unknown11'             => Binary::UNSIGNED_CHAR(344, Binary::RAW_FILTER_HEX),
                    'GCBoard'               => Binary::UNSIGNED_CHAR(172, Binary::RAW_FILTER_HEX),
                    'unknown12'             => Binary::UNSIGNED_CHAR(200, Binary::RAW_FILTER_HEX),
                    'challengeData'         => Binary::UNSIGNED_CHAR(320, Binary::RAW_FILTER_HEX),
                    'unknown13'             => Binary::UNSIGNED_CHAR(172, Binary::RAW_FILTER_HEX),
                    'unknown14'             => Binary::UNSIGNED_CHAR(276, Binary::RAW_FILTER_HEX),
                    'keyConfigGlobal'       => Binary::UNSIGNED_CHAR(364, Binary::RAW_FILTER_HEX),
                    'joyConfigGlobal'       => Binary::UNSIGNED_CHAR(56, Binary::RAW_FILTER_HEX),
                    'guildCard2'            => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'teamID'                => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'teamInformation'       => Binary::UNSIGNED_CHAR(8, Binary::RAW_FILTER_HEX),
                    'privilegeLevel'        => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'reserved3'             => Binary::UNSIGNED_SHORT(null, Binary::RAW_FILTER_PACK),
                    'teamName'              => Binary::UNSIGNED_CHAR(28, Binary::RAW_FILTER_HEX),
                    'unknown15'             => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK),
                    'teamFlag'              => Binary::UNSIGNED_CHAR(2048, Binary::RAW_FILTER_HEX),
                    'teamRewards'           => Binary::UNSIGNED_CHAR(8, Binary::RAW_FILTER_HEX),
                ], Binary::Stream($data));

                dd($parser);
            }
        }
    }
}