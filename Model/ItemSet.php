<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/26
 * Time: 下午7:55
 */

namespace Model;


use Kernel\DB;
use Model\Traits\ItemsUtility;

class ItemSet
{
    use ItemsUtility;

    private $ITEMS = [];

    private $MST = 0;

    /**
     * ItemSet constructor.
     *
     * @param array $items [[code, num], ...]
     */
    public function __construct(array $items = [], $mst = 0) {
        $this->ITEMS = $items;
        $this->MST = $mst;
    }

    public static function make($name) {
        $items = null;
        $mst = 0;

        if ($name) {
            $itemSet = DB::connection()->where('name', $name)->getOne('item_set');
            $items = json_decode($itemSet['items'], true);
            $mst = intval($itemSet['mst']);
        }

        return new static($items, $mst);
    }

    public function getMST() {
        return $this->MST;
    }

    public function toBankItems() {
        $items = $this->ITEMS;
        if (!empty($items)) {
            $items = collect($items)->map(function ($item) {
                $item = BankItem::make($item[0], $item[1]);

                return $item->isValid() ? $item : null;
            })->filter()->toArray();
        }

        return $items;
    }

    public function count() {
        return count($this->ITEMS);
    }
}