<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/24
 * Time: 下午11:07
 */

namespace Model;

use Codante\Binary\Binary;
use Kernel\Config;

class BankItem extends Item
{
    public $num;

    public function __construct($code = null, $num = 0, $itemid = -1) {
        parent::__construct($code, $itemid);
        $this->setNum($num);
    }

    public function data() {
        return [
            'data'       => $this->data,
            'itemid'     => $this->isValid() ? $this->itemid | 0x10000 : -1,
            'data2'      => $this->data2,
            'bank_count' => $this->isValid() ? $this->num | 0x10000 : 0,
        ];
    }

    public function toBin() {
        return Binary::Builder(Config::get('DATA_STRUCTURE.BANK_ITEM'), $this->data())->stream()->all();
    }

    public static function fromBin($bin) {
        $_unpacked = Binary::Parser(Config::get('DATA_STRUCTURE.BANK_ITEM'), Binary::Stream($bin))->data();

        return new static($_unpacked['data'] . $_unpacked['data2'], $_unpacked['bank_count'] &
                                                                    0xFFFF, $_unpacked['itemid'] & 0xFFFF);
    }

    public static function make($code = null, $num = 0, $itemid = 0) {
        return new static($code, $num, $itemid);
    }

    public function setNum($num = 0) {
        // 增加允许条件

        $num = intval($num);

        if ($num < 0) {
            $num = 0;
        } elseif ($num > 99) {
            $num = 99;
        }

        $this->num = $num;
    }

    public function isValid() {
        return $this->hex != '000000' && $this->num > 0;
    }
}