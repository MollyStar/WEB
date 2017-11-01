<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/26
 * Time: 下午8:29
 */

namespace Model\Traits;


trait ItemsUtility
{
    public function itemsHEX() {
        return collect($this->ITEMS)->pluck('item')->unique()->toArray();
    }
}