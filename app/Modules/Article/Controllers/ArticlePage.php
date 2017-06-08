<?php

namespace App\Modules\Article\Controllers;

class ArticlePage
{
    public function index()
    {
        return [
            'tpl' => 'Article/detail.tpl',
            'data' => [
            ]
        ];
    }
}