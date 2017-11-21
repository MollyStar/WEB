<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/14
 * Time: 上午11:05
 */

namespace Model;


use Carlosocarvalho\SimpleInput\Input\Input;
use Kernel\DB;
use \Exception;

class UserFeature
{

    public function __construct($guildcard) {

    }

    public static function make($guildcard) {
        return new static($guildcard);
    }

    public static function capture() {
        $feature = Input::post('USER_FEATURE') ?? null;
        if (is_null($feature)) {
            throw new Exception('异常的浏览器！请使用IE10+/Chrome/Safari/Firefox或品牌浏览器的极速模式');
        }

        return $feature;
    }

    public static function build($guildcard, $feature = '') {
        if ($guildcard > 0 && $feature = self::parse($feature)) {
            if (!DB::connection()
                ->where('hash', $feature['hash'])
                ->where('guildcard', $guildcard)
                ->getOne('user_feature')
            ) {
                DB::connection()->insert('user_feature', array_merge(['guildcard' => $guildcard], $feature));
            }
        }
    }

    public static function parse($feature = '') {
        if (is_string($feature)) {
            $feature = json_decode($feature, true);
        }

        if (empty($feature)) {
            return null;
        }

        foreach ($feature as & $item) {
            if (is_bool($item)) {
                $item = intval($item);
            } elseif (is_array($item)) {
                $item = join("\t", $item);
            }
        }

        return $feature;
    }

    public function verify() {

    }

    public function add(array $feature) {

    }
}