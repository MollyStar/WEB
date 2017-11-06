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

class Item
{
    public $hex;
    public $detail;
    public $code;
    public $data;
    public $itemid;
    public $data2; // for mag

    public function __construct($code = null, $itemid = -1) {
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

        switch (hexdec(substr($code, 0, 2))) {
            case 0x00: // 武器
                $this->hex = substr($code, 0, 6);
                $this->detail = new Weapon($code);
                break;
            case 0x01: // 防具
                $this->hex = substr($code, 0, 6);
                break;
            case 0x02: // 马古
                $this->hex = substr($code, 0, 4) . '00';
                $this->detail = new Mag($code);
                break;
            case 0x03: // TODO 物品，未细分
                $this->hex = substr($code, 0, 6);
                if ('0302' === substr($code, 0, 4)) {
                    $this->detail = new Disc($code);
                }
                break;
            default:
                $this->hex = substr($code, 0, 6);
        }

        $this->setItemid($itemid);
    }

    public function data() {
        return [
            'data'   => $this->data,
            'itemid' => $this->isValid() ? $this->itemid | 0x10000 : -1,
            'data2'  => $this->data2,
        ];
    }

    public function toBin() {
        return Binary::Builder(Config::get('DATA_STRUCTURE.ITEM'), $this->data())->stream()->all();
    }

    public static function fromBin($bin) {
        $_unpacked = Binary::Parser(Config::get('DATA_STRUCTURE.ITEM'), Binary::Stream($bin))->data();

        return new static($_unpacked['data'] . $_unpacked['data2'], $_unpacked['itemid'] & 0xFFFF);
    }

    public static function make($code = null, $itemid = 0) {
        return new static($code, $itemid);
    }

    public function setItemid($id = -1) {
        if (is_numeric($id)) {
            $this->itemid = $id;
        }
    }

    public function isValid() {
        return $this->hex != '000000';
    }
}