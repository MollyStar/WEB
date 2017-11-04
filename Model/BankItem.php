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

class BankItem
{
    public $hex;
    public $detail;
    public $code;
    public $data;
    public $data2; // for mag
    public $num;
    public $itemid;

    public function __construct($code = null, $num = 0, $itemid = 0) {
        if (is_array($code)) {
            $code = join('', $code);
        } elseif (is_string($code)) {
            $code = str_replace(',', '', $code);
        }
        if (is_null($code) || strlen($code) !== 32) {
            $code = str_repeat('0', 32);
        }
        $code = strtoupper($code);
        $this->code = $code;
        $this->data = substr($code, 0, 24);
        $this->data2 = substr($code, -8);
        $this->setNum($num);
        $this->setItemid($itemid);

        switch (hexdec(substr($code, 0, 2))) {
            case 0x00: // 武器
                $this->hex = substr($code, 0, 6);
                break;
            case 0x01: // 防具
                $this->hex = substr($code, 0, 6);
                break;
            case 0x02: // 马古
                $this->hex = substr($code, 0, 4) . '00';
                break;
            case 0x03: // TODO 物品，未细分
                $this->hex = substr($code, 0, 6);
                break;
            default:
                $this->hex = substr($code, 0, 6);
        }
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

    public function setItemid($id = 0) {
        if ($id < 0) {
            $id = 0;
        } elseif ($id > 199) {
            $id = 199;
        }

        $this->itemid = $id;
    }

    public function isValid() {
        return $this->hex != '000000' && $this->num > 0;
    }
}