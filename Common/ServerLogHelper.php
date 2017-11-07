<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/27
 * Time: 下午10:02
 */

namespace Common;


class ServerLogHelper
{
    private static $_CACHE;

    private static $_LAST_M_TIME = 0;

    public static function ship_logs() {
        $d = dir(ROOT . '/__SERVER/log');
        $files = [];

        $mtime = 0;

        while (false !== ($entry = $d->read())) {
            $p = pathinfo($entry);
            if ($p['extension'] == 'log') {
                $file = realpath(ROOT . '/__SERVER/log/' . $entry);
                $t = filemtime($file);
                if ($mtime < $t) {
                    $mtime = $t;
                }
                $files[] = $file;
            }
        }
        $d->close();

        if (self::$_LAST_M_TIME === $mtime) {
            return self::$_CACHE;
        }

        self::$_LAST_M_TIME = $mtime;

        return self::$_CACHE = collect($files)->map(function ($path) {
            $rows = file($path);

            return collect($rows)->map(function ($row) {
                $row = trim($row, "\r\n");
                if ($row) {
                    preg_match('/\[([^\]]+)\] User (\d+).+has (connected|disconnected)/', $row, $match);

                    if ($match) {
                        preg_match('/(\d+)-(\d+)-(\d+), (\d+)\:(\d+)/', $match[1], $match2);

                        return [
                            'time'      => mktime($match2[4], $match2[5], 0, $match2[1], $match2[2], $match2[3]),
                            'guildcard' => $match[2],
                            'online'    => $match[3] == 'connected' ? true : false,
                        ];
                    }
                }

                return null;
            });
        })->flatten(1)->filter()->sortByDesc('time')->groupBy('guildcard')->map(function ($row) {
            return $row->first();
        })->toArray();
    }
}