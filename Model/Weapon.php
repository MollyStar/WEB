<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/3
 * Time: 下午8:24
 */

namespace Model;


use Kernel\Config;

class Weapon
{
    public $st1;
    public $st2;
    public $st3;
    public $strengthen = 0;

    public function __construct($code) {
        $this->strengthen = hexdec(substr($code, 6, 2));
        $conf = Config::get('server.weapon');
        $stat = collect(str_split(substr($code, 12, 12), 4))->map(function ($item) use ($conf) {
            list($st, $val) = str_split($item, 2);
            $st = hexdec($st);
            $val = hexdec($val);
            if ($st > 0) {
                return ['st' => $st, 'name' => $conf[$st][0], 'name_zh' => $conf[$st][1], 'val' => $val];
            }

            return null;
        })->filter()->sortBy('st')->toArray();

        $this->st1 = $stat[0] ?? null;
        $this->st2 = $stat[1] ?? null;
        $this->st3 = $stat[2] ?? null;
    }
}