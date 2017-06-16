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

    public function edit()
    {
        return [
            'tpl' => 'Article/edit.tpl',
            'data' => [
            ]
        ];
    }
}