<?php

namespace Controller\Tools;


use Kernel\DB;
use Kernel\Response;

class DBStructure
{

    public function index() {
        $DB_Status = collect(DB::connection()->query('SHOW TABLE STATUS'));

        $DB_Status->transform(function ($item) {
            $item['columns'] = DB::connection()->query('SHOW FULL COLUMNS FROM `' . $item['Name'] . '`');
            // dd($item->columns);
            $item['keys'] = DB::connection()->query('SHOW KEYS FROM `' . $item['Name'] . '`');

            return $item;
        });

        $indexes = $DB_Status->pluck('Name')->map(function ($item) {
            return [
                'Table_name' => $item,
                'Index'      => strtoupper(substr($item, 0, 1)),
            ];
        })->groupBy('Index');

        return Response::view('pages.tools.db', [
            'indexes'     => $indexes,
            'DBStructure' => $DB_Status,
            'column_head' => ['键名', '类型', '整理', '空', '索引', '默认', '额外', '备注'],
            'key_head'    => ['键名', '类型', '唯一', '紧凑', '字段', '排序规则', '空', '注释'],
        ]);
    }
}
