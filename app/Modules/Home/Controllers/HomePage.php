<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\Controller;
use Auth;

/**
 * Created by PhpStorm.
 * User: Ray
 * Date: 17/6/1
 * Time: 下午6:16
 */
class HomePage extends Controller
{
    public function index()
    {
        $this->setTpl('Home/home.tpl');
        return $this->tplOutput([
            'config' => json_encode(config('constants.group')),
        ]);
    }
}