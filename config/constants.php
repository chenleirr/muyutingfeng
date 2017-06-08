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
        'other' => [
            'dont_touch' => [
                'code' => 101,
                'desc' => '随笔'
            ],
        ],
        'technical' => [
            'laravel' => [
                'code' => 201,
                'desc' => 'Laravel'
            ],
            'mysql' => [
                'code' => 202,
                'desc' => 'Mysql'
            ],
        ],
    ],
];