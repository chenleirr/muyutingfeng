<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RequestApiServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'requestapi';
    }
}