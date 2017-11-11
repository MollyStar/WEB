<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/11
 * Time: 下午11:00
 */

namespace Common;


class CharacterHelper
{
    public static function decode_name($hex) {
        return str_replace(chr(0), '', mb_convert_encoding(hex2bin(substr($hex, 8)), 'UTF-8', 'byte2le'));
    }

    public static function encode_name($str) {
        return strtoupper(sprintf('%0-48s', '09004500' . bin2hex(mb_convert_encoding($str, 'byte2le', 'UTF-8'))));
    }
}