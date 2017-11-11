<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/25
 * Time: 下午12:09
 */

namespace Model;

class InventoryItem extends Item
{
    public $equipped = false;

    public $in_use = false;

    public function __construct($code = null, $itemid = -1, $in_use = 0, $equipped = 0) {
        parent::__construct($code, $itemid);
        $this->setUse($in_use);
        $this->setEquip($equipped);
    }

    public static function make($code = null, $itemid = -1, $in_use = 0, $equipped = 0) {
        return new static($code, $itemid, $in_use, $equipped);
    }

    public function inUse() {
        return $this->in_use;
    }

    public function setUse($in_use = 0) {
        $this->in_use = $in_use === 1;
    }

    public function isEquipped() {
        return $this->equipped;
    }

    public function setEquip($flag = 0) {
        $this->equipped = $flag === 8;
    }
}