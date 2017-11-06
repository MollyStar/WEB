<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/6
 * Time: 下午4:37
 */

namespace Model;


use Codante\Binary\Binary;
use Kernel\Config;

class Mag
{
    public $data;
    public $defense;
    public $power;
    public $dex;
    public $mind;
    public $synchro;
    public $IQ;

    public function __construct($code) {
        $this->data = Binary::Parser(Config::get('DATA_STRUCTURE.MAG'), Binary::Stream(hex2bin($code)))->data();
        $this->defense = [(int)substr($this->data['defense'], 0, -2), (int)substr($this->data['defense'], -2)];
        $this->power = [(int)substr($this->data['power'], 0, -2), (int)substr($this->data['power'], -2)];
        $this->dex = [(int)substr($this->data['dex'], 0, -2), (int)substr($this->data['dex'], -2)];
        $this->mind = [(int)substr($this->data['mind'], 0, -2), (int)substr($this->data['mind'], -2)];
        $this->synchro = hexdec($this->data['synchro']);
        $this->IQ = hexdec($this->data['IQ']);
    }
}