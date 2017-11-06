<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/6
 * Time: 下午4:37
 */

namespace Model;


use Kernel\Config;

class Disc
{
    public $level;
    public $type;
    public $name;
    public $name_zh;

    public function __construct($code) {
        $this->level = hexdec(substr($code, 4, 2)) + 1;
        $this->type = hexdec(substr($code, 8, 2));
        $disc = Config::get('server.disc')[$this->type];
        $this->name = $disc[0];
        $this->name_zh = $disc[1];
    }
}