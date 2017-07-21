<?php

return [
    'article' => [
        'status' => [
            'normal' => [
                'code' => 1,
                'desc' => '正常'
            ],
            'delete' => [
                'code' => 2,
                'desc' => '删除'
            ],
        ],
    ],
    'group' => [
        'dont_touch' => [
            'code' => 101,
            'desc' => '随笔'
        ],
//=====================================
        'laravel' => [
            'code' => 201,
            'desc' => 'Laravel'
        ],
        'mysql' => [
            'code' => 202,
            'desc' => 'Mysql'
        ],
        'php' => [
            'code' => 203,
            'desc' => 'PHP'
        ],
        'nginx' => [
            'code' => 204,
            'desc' => 'Nginx'
        ],
        'web' => [
            'code' => 205,
            'desc' => '网络'
        ],
    ],
];