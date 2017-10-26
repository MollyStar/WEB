<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 下午11:13
 */

return [
    'ep'    => [
        [1, 'I', 'Episode: I'],
        [2, 'II', 'Episode: II'],
        [4, 'IV', 'Episode: IV'],
    ],
    'type'  => [
        'box',
        'mob',
    ],
    'dif'   => [
        ['Normal', '普通', 'N'],
        ['Hard', '困难', 'H'],
        ['Very Hard', '极难', 'V'],
        ['Ultimate', '极限', 'U'],
    ],
    'sec'   => [
        ['VIRIDIA', '铬绿', '#94ffbc'],
        ['GREENNILL', '翠绿', '#97ff94'],
        ['SKYLY', '天青', '#94fffe'],
        ['BLUEFULL', '纯蓝', '#94c9ff'],
        ['PURPLENUM', '淡紫', '#ba94ff'],
        ['PINKAL', '粉红', '#ff94d0'],
        ['REDRIA', '真红', '#ff9494'],
        ['ORAN', '橙黄', '#ffdd94'],
        ['YELLOWBOZE', '金黄', '#f9ff94'],
        ['WHITILL', '羽白', '#ffffff'],
    ],
    'area'  => [
        [
            [
                ['Forest', '森林'],
                [
                    1  => ['Forest 1', '森林一层'],
                    2  => ['Forest 2', '森林二层'],
                    11 => ['Dragon', '龙穴'],
                ],
            ],
            [
                ['Cave', '洞窟'],
                [
                    3  => ['Cave 1', '洞窟一层'],
                    4  => ['Cave 2', '洞窟二层'],
                    5  => ['Cave 3', '洞窟三层'],
                    12 => ['De Rol', 'BOSS区域'],
                ],
            ],
            [
                ['Mine', '坑道'],
                [
                    6  => ['Mine 1', '坑道一层'],
                    7  => ['Mine 2', '坑道二层'],
                    13 => ['Vol opt', 'BOSS区域'],
                ],
            ],
            [
                ['Ruins', '遗迹'],
                [
                    8  => ['Ruins 1', '遗迹一层'],
                    9  => ['Ruins 2', '遗迹二层'],
                    10 => ['Ruins 3', '遗迹三层'],
                    14 => ['Falz', '暗黑佛区域'],
                ],
            ],
            [
                ['Other', '其他'],
                [
                    0  => ['Pioneer 2', '先驱者2号'],
                    15 => ['Lobby 1', '大厅1'],
                ],
            ],
        ],
        [
            [
                ['Temple', '神殿'],
                [
                    1  => ['Temple alpha', '神殿α'],
                    2  => ['Temple beta', '神殿β'],
                    14 => ['Barba ray', 'BOSS区域'],
                ],
            ],
            [
                ['Space Ship', '宇宙船'],
                [
                    3  => ['Space Ship alpha', '宇宙船α'],
                    4  => ['Space Ship beta', '宇宙船β'],
                    15 => ['Gol dragon', '数码龙'],
                ],
            ],
            [
                ['CCA', '中央管理区'],
                [
                    5  => ['CCA', '中央管理区'],
                    6  => ['Jungel east', '雨林东部地区'],
                    7  => ['Jungel north', '雨林北部地区'],
                    8  => ['Mountains', '高山地区'],
                    9  => ['Seaside', '海岸地区'],
                    12 => ['Gal Gryphon', '加尔狮鹫地区'],
                ],
            ],
            [
                ['Seabed', '海底'],
                [
                    10 => ['Seabed upper', '海底上层'],
                    11 => ['Seabed lower', '海底下层'],
                    13 => ['Olga Flow', 'BOSS区域'],
                ],
            ],
            [
                ['Tower', '中央控制塔'],
                [
                    17 => ['Tower', '中央控制塔'],
                ],
            ],
        ],
        [
            [
                ['Wilds', '陨石坑'],
                [
                    1 => ['Wilds', '沙漠??'],
                    2 => ['Wilds', '沙漠??'],
                    3 => ['Wilds', '沙漠??'],
                    4 => ['Wilds', '沙漠??'],
                    5 => ['Crater', '陨石坑'],
                ],
            ],
            [
                ['Desert', '地下沙漠'],
                [
                    6 => ['Desert 1', '沙漠一层'],
                    7 => ['Desert 2', '沙漠二层'],
                    8 => ['Desert 3', '沙漠三层'],
                    9 => ['EP4 Boss', 'BOSS区域'],
                ],
            ],
        ],
    ],
    'ports' => [
        '12000' => ['login', '登录服务器'],
        '11000' => ['patch', '更新服务器'],
        '5278'  => ['SHIP 1', '正式 船1'],
        '5378'  => ['SHIP Beta', '测试 船BETA'],
    ],
];