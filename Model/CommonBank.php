<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/25
 * Time: 下午12:09
 */

namespace Model;


use Codante\Binary\Binary;
use Kernel\Config;
use Model\Traits\ItemsUtility;

class CommonBank
{
    use ItemsUtility;

    private $MST;
    private $USE;
    private $ITEMS;

    private $SOCK = 200;
    private $MAX_MST = 999999;

    const BIN_LENGTH = 4808;

    public function __construct() {
        $this->MST = 0;
        $this->USE = 0;
        $this->ITEMS = array_fill(0, $this->SOCK, BankItem::make());
    }

    public static function make($bin = null) {
        if (is_string($bin) && strlen($bin) === self::BIN_LENGTH) {
            return self::fromBin($bin);
        }

        return new static();
    }

    public static function fromBin(string $bin) {

        if (strlen($bin) !== self::BIN_LENGTH) {
            throw new \Exception('[ERROR] Bank data length!');
        }
        $handler = new static();
        $_unpacked = Binary::Parser(Config::get('DATA_STRUCTURE.BANK'), Binary::Stream($bin))->data();

        $handler->setMST($_unpacked['meseta']);

        collect($_unpacked['bankInventory'])->each(function ($item) use (&$handler) {
            $item = BankItem::make($item['data'] . $item['data2'], $item['bank_count'] - 0x00010000, $item['itemid'] -
                                                                                                     0x00010000);
            if ($item->isValid()) {
                $handler->addItem($item);
            }
        });

        return $handler;
    }

    public function setMST($num = 0) {
        if ($num < 0) {
            $num = 0;
        } elseif ($num > $this->MAX_MST) {
            $num = 999999;
        }

        $this->MST = $num;
    }

    public function getMST() {
        return $this->MST;
    }

    public function getFreeMST() {
        return $this->MAX_MST - $this->MST;
    }

    public function addItem(BankItem $item) {
        if ($this->USE == $this->SOCK) {
            return -1;
        }
        $itemid = $this->USE++;
        $item->setItemid($itemid);
        $this->ITEMS[$itemid] = $item;

        return $itemid;
    }

    /**
     * 获取物品列表
     *
     * @param bool $valid true: only used socks, false: all
     *
     * @return array
     */
    public function items($valid = false) {
        if (!!$valid) {
            return collect($this->ITEMS)->filter(function ($item) {
                return $item->isValid();
            })->toArray();
        } else {
            return $this->ITEMS;
        }
    }

    public function toBin() {
        return pack('II', $this->USE, $this->MST) . collect($this->ITEMS)->map(function ($item) {
                return $item->toBin();
            })->implode('');
    }

    public function used() {
        return $this->USE;
    }

    public function remaining() {
        return $this->SOCK - $this->USE;
    }
}