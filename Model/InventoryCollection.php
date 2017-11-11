<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/25
 * Time: 下午12:09
 */

namespace Model;

class InventoryCollection extends Bank
{
    protected $SOCK = 30;

    public function fillInventory($bankInventory = []) {
        collect($bankInventory)->each(function ($slot) {
            $slot = InventoryItem::make($slot['item']['data'] . $slot['item']['data2'], $slot['item']['itemid'] &
                                                                                        0xFFFF, $slot['in_use'], $slot['flag']);
            if ($slot->isValid()) {
                $this->addItem($slot);
            }
        });
    }

    public function addItem($item) {
        if ($this->USE == $this->SOCK) {
            return -1;
        }
        $itemid = $this->USE++;
        $item->setItemid($itemid);
        $this->ITEMS[$itemid] = $item;

        return $itemid;
    }
}