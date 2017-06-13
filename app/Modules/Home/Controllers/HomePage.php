<?php

namespace App\Modules\Home\Controllers;

/**
 * Created by PhpStorm.
 * User: Ray
 * Date: 17/6/1
 * Time: 下午6:16
 */
class HomePage
{
    public function index()
    {
        return [
            'tpl' => 'Home/home.tpl',
            'data' => [
                'config' => json_encode(config('constants.group')),
            ]
        ];
    }
}