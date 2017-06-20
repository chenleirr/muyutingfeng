<?php

namespace App\Modules\Article\Controllers;

use App\Http\Controllers\Controller;

class ArticlePage extends Controller
{
    public function index()
    {
        $this->setTpl('Article/detail.tpl');
        return $this->tplOutput();
    }

    public function edit()
    {
        $this->setTpl('Article/edit.tpl');
        return $this->tplOutput();
    }
}