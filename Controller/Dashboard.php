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
use Codante\Binary\Parser;
use Codante\Binary\Stream;
use Kernel\DB;
use Kernel\Response;

class Dashboard
{
    public function index() {
        return Response::view('pages.dashboard');
    }

    public function test() {
        $guildcard = Input::get('guildcard');
        if ($guildcard > 0) {

            $user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data');

            if ($user) {

                $data = DB::connection()->where('guildcard', $guildcard)->getValue('bank_data', 'data');

                $parser = new Parser([
                    'used'  => Binary::UNSIGNED_INTEGER(null, 1),
                    'mst'   => Binary::UNSIGNED_INTEGER(null, 1),
                    'items' => [
                        [
                            'set0'   => Binary::UNSIGNED_CHAR(null, 2),
                            'set1'   => Binary::UNSIGNED_CHAR(null, 1),
                            'set2'   => Binary::UNSIGNED_CHAR(null, 1),
                            'itemid' => Binary::UNSIGNED_INTEGER(null, 1, function ($value) {
                                return $value - 0x00010000;
                            }),
                            'set3'   => Binary::UNSIGNED_CHAR(null, 1),
                            'num'    => Binary::UNSIGNED_INTEGER(null, 1, function ($value) {
                                return $value - 0x00010000;
                            }),
                        ],
                        200,
                    ],
                    //                    'items' => Binary::COLLECTION([
                    //                        'set0'   => Binary::UNSIGNED_CHAR(),
                    //                        'set1'   => Binary::UNSIGNED_CHAR(),
                    //                        'set2'   => Binary::UNSIGNED_CHAR(),
                    //                        'num'    => Binary::UNSIGNED_INTEGER(),
                    //                        'set3'   => Binary::UNSIGNED_CHAR(),
                    //                        'itemid' => Binary::UNSIGNED_INTEGER(),
                    //                    ], 200),
                ], new Stream($data));

                dd($parser->data());
            }
        }
    }
}