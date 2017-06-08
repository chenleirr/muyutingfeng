<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ArticleServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'articleservice';
    }
}