<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 1:12
 */

namespace Controller;

use Carlosocarvalho\SimpleInput\Input\Input;
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

                $structure = new Collection([
                    'used'  => ['int', 4],
                    'mst'   => ['int', 4],
                    'items' => [
                        'arr',
                        200,
                        [
                            'code'   => ['string', 12],
                            'num'    => ['int', 4],
                            'mag'    => ['string', 4],
                            'itemid' => ['int', 4],
                        ],
                    ],
                ]);

                dd($structure->parse(new StringStream($data)));
            }
        }
    }
}