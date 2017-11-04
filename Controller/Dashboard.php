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

        $guildcard = Input::get('guildcard');
        if ($guildcard > 0) {

            $user = DB::connection()->where('guildcard', $guildcard)->getOne('account_data');

            if ($user) {

                $data = DB::connection()->where('guildcard', $guildcard)->getValue('character_data', 'data');

                $parser = Binary::Parser(Config::get('DATA_STRUCTURE.CHARDATA'), Binary::Stream($data));

                dd($parser->data());

                $builder = Binary::Builder(Config::get('DATA_STRUCTURE.CHARDATA'), $parser->data());
                dd($builder->stream()->all() === $parser->stream()->all());
            }
        }
    }
}