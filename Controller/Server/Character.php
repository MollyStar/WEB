<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/24
 * Time: 下午6:48
 */

namespace Controller\Server;


use Carlosocarvalho\SimpleInput\Input\Input;
use Kernel\DB;
use Kernel\Response;
use Model\CommonBankItem;

class Character
{
    public function manage() {
        $character_list = collect(DB::connection()
            ->join('account_data as ad', 'ad.guildcard = cd.guildcard', 'INNER')
            ->orderBy('ad.guildcard', 'asc')
            ->get('character_data as cd'))->map(function ($item) {
            $item['data'] = bin2hex($item['data']);
            $item['header'] = bin2hex($item['header']);
            $item['lasthwinfo'] = bin2hex($item['lasthwinfo']);
            $item['lastchar'] = bin2hex($item['lastchar']);
            $item['regtime'] = date('Y-m-d H:i:s', $item['regtime'] * 3600);

            return $item;
        })->groupBy('guildcard')->map(function ($item) {
            $new_item = collect($item[0])->only([
                'guildcard',
                'username',
                'regtime',
                'lastip',
                'isgm',
                'isbanned',
                'islogged',
                'isactive',
            ]);
            $new_item['characters'] = collect($item)->map(function ($item) {
                return collect($item)->only(['slot', 'data', 'header']);
            });
            $new_item['character_num'] = count($new_item['characters']);

            return $new_item;
        })->toArray();

        dd($character_list);

        return Response::view('pages.character', compact('character_list'));
    }

    public function bank() {

        $guildcard = Input::get('guildcard');
        if ($guildcard > 0) {
            $data = DB::connection()->where('guildcard', $guildcard)->getValue('bank_data', 'data');
            extract(unpack('IbankUse/IbankMeseta', substr($data, 0, 8)));
            $items = collect(str_split(substr($data, 8), 24))->map(function ($bankItem) {
                $item['BIN'] = $bankItem;
                $item['HEX'] = bin2hex($bankItem);

                $item['COLLECT'] = collect(unpack('C12data1:2:/Iitemid:8/C4data2:2:/Ibank_count:8', $bankItem))->mapWithKeys(function ($value, $key) {
                    $key_args = explode(':', $key);

                    $len = $key_args[1];
                    unset($key_args[1]);

                    return [join(':', $key_args) => $value]; //sprintf('%0' . $len . 'X', $value)];
                });

                return $item;
            });

            dd((new CommonBankItem('00080606,0000025f,011e052d,00000000', 1))->toBankRaw(), $items);
        }
    }
}