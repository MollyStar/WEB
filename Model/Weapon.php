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
    public $hex;
    public $st1;
    public $st2;
    public $st3;
    public $strengthen = 0;
    public $special;
    public $abnormal = false;

    public function __construct($code) {
        $this->hex = substr($code, 0, 6);
        $this->strengthen = hexdec(substr($code, 6, 2));
        $map_sp = Config::get('server.special');
        $special = hexdec(substr($code, 8, 2));
        $map_asp = Config::get('server.allowed_special');
        if ($special > 0 && isset($map_sp[$special])) {
            $this->special = $map_sp[$special];
            if (!in_array($special, [0x50, 0xBF, 0xFF]) && !in_array($this->hex, $map_asp)) {
                $this->abnormal = true;
            }
        }
        $map_st = Config::get('server.weapon');
        $stat = collect(str_split(substr($code, 12, 12), 4))->map(function ($item) use ($map_st) {
            list($st, $val) = str_split($item, 2);
            $st = hexdec($st);
            $val = hexdec($val);
            if ($st > 0) {
                return [
                    'st'      => $st,
                    'name'    => $map_st[$st][0],
                    'name_zh' => $map_st[$st][1],
                    'val'     => $val,
                ];
            }

            return null;
        })->filter()->sortBy('st')->toArray();

        $this->st1 = $stat[0] ?? null;
        $this->st2 = $stat[1] ?? null;
        $this->st3 = $stat[2] ?? null;
    }
}