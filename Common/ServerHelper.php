<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/18
 * Time: 下午11:39
 */

namespace Common;


use Kernel\Config;
use Kernel\DB;

class ServerHelper
{
    public static function mapLvArea() {
        $c = Config::get('server.area');

        $t = [];
        foreach ($c as $ek => $ep) {
            $t[$ek] = [];
            foreach ($ep as $ak => $area) {
                foreach ($area[1] as $lk => $lv) {
                    $t[$ek][$lk] = $ak;
                }
            }
        }

        return $t;
    }

    public static function mapMobArea() {
        return collect(DB::connection()->get('map_drop_mob', null, ['ep', 'name', 'area']))
            ->groupBy('ep')
            ->map(function ($item) {
                return array_combine($item->pluck('name')->toArray(), $item->pluck('area')->toArray());
            })
            ->toArray();
    }

    /**
     * From tethealla_source
     *
     * @param $pc
     *
     * @return int
     */
    public static function ExpandDropRate($pc = 0) {
        $pc &= 0xFF;
        $shift = (($pc >> 3) & 0x1F) - 4;
        if ($shift < 0) {
            $shift = 0;
        }

        return (2 << $shift) * (($pc & 7) + 7);
    }

    public static function DropRatePercent($rate = 0) {
        return 1 * sprintf('%.6f', self::ExpandDropRate($rate) / 0xFFFFFFFF * 100) . '%';
    }
}