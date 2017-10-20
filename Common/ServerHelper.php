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
}