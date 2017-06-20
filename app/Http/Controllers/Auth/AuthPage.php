<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Auth;

class AuthPage extends Controller
{
    public function register()
    {
        $this->setTpl('Auth/register.tpl');
        return $this->tplOutput();
    }

    public function login()
    {
        $this->setTpl('Auth/login.tpl');
        return $this->tplOutput();
    }
}