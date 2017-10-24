<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/24
 * Time: 下午11:07
 */

namespace Model;

class CommonBankItem
{

    public $item;
    public $strengthen;
    public $code;
    public $set1;
    public $set2;
    public $set3;
    public $set4; // for mag
    public $num;

    public function __construct($code = '', $num = 0) {
        if (is_array($code)) {
            $code = join('', $code);
        } elseif (is_string($code)) {
            $code = str_replace(',', '', $code);
        } else {
            $code = str_repeat('0', 32);
        }
        $code = strtoupper($code);
        $this->code = $code;
        $this->item = substr($code, 0, 6);
        $this->strengthen = hexdec(substr($code, 6, 2));
        list($this->set1, $this->set2, $this->set3, $this->set4) = str_split($code, 8);

        $this->setNum($num);
    }

    public function toBankRaw() {

        if ($this->item == '000000' || $this->num == 0) {
            $hex_arr = array_fill(0, 18, 0);
            $hex_arr[12] = -1;
        } else {
            $hex_arr = array_merge(str_split($this->set1, 2), str_split($this->set2, 2), str_split($this->set3, 2), ['00010000'], str_split($this->set4, 2), [
                '0001' . sprintf('%04X', $this->num),
            ]);

            foreach ($hex_arr as &$item) {
                $item = hexdec($item);
            }
        }

        return call_user_func_array('pack', array_merge(['C12IC4I'], $hex_arr));
    }

    public static function fromBankRaw($bin) {

    }

    public function setNum($num = 0) {
        if ($num < 0) {
            $num = 0;
        } elseif ($num > 99) {
            $num = 99;
        }

        $this->num = $num;
    }
}