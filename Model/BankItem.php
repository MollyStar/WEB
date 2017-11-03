<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/24
 * Time: 下午11:07
 */

namespace Model;

use Codante\Binary\Binary;

class BankItem
{

    public $hex;
    public $strengthen;
    public $code;
    public $set1;
    public $set2;
    public $set3;
    public $set4; // for mag
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
        if (substr($code, 0, 2) === '02') {
            // 马古
            $this->hex = substr($code, 0, 4) . '00';
        } else {
            $this->hex = substr($code, 0, 6);
        }
        $this->strengthen = hexdec(substr($code, 6, 2));
        list($this->set1, $this->set2, $this->set3, $this->set4) = str_split($code, 8);

        $this->setNum($num);
        $this->setItemid($itemid);
    }

    public function toBin() {

        if (!$this->isValid()) {
            $hex_arr = array_fill(0, 18, 0);
            $hex_arr[12] = -1;
        } else {
            $hex_arr = array_merge(str_split($this->set1, 2), str_split($this->set2, 2), str_split($this->set3, 2), [
                sprintf('%08X', $this->itemid + 0x00010000),
            ], str_split($this->set4, 2), [
                sprintf('%08X', $this->num + 0x00010000),
            ]);

            foreach ($hex_arr as &$item) {
                $item = hexdec($item);
            }
        }

        return call_user_func_array('pack', array_merge(['C12IC4I'], $hex_arr));
    }

    public static function fromBin($bin) {
        $_up = (Binary::Parser([
            'set1'   => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
            'set2'   => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
            'set3'   => Binary::UNSIGNED_CHAR(null, Binary::RAW_FILTER_HEX),
            'itemid' => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK, function ($res) {
                return $res - 0x00010000;
            }),
            'set4'   => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_HEX),
            'num'    => Binary::UNSIGNED_INTEGER(null, Binary::RAW_FILTER_PACK, function ($res) {
                return $res - 0x00010000;
            }),
        ]))->parse(Binary::Stream($bin));

        return new static($_up['set1'] . $_up['set2'] . $_up['set3'] . $_up['set4'], $_up['num'], $_up['itemid']);
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