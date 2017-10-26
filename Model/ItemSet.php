<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/26
 * Time: 下午7:55
 */

namespace Model;


use Kernel\DB;
use Model\Traits\Items;

class ItemSet
{
    use Items;

    private $ITEMS = [];

    /**
     * ItemSet constructor.
     *
     * @param array $items [[code, num], ...]
     */
    public function __construct(array $items = []) {
        $this->ITEMS = $items;
    }

    public static function make($name) {
        $items = null;

        if ($name) {
            $items = json_decode(DB::connection()->where('name', $name)->getValue('item_set', 'items'), true);
        }

        return new static($items);
    }

    public function wrappedCommonBankItems() {
        $items = $this->ITEMS;
        if (!empty($items)) {
            $items = collect($items)->map(function ($item) {
                return CommonBankItem::make($item[0], $item[1]);
            })->toArray();
        }

        return $items;
    }

    public function count() {
        return count($this->ITEMS);
    }
}