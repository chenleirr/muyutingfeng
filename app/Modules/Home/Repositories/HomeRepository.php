<?php

namespace App\Modules\Home\Repositories;

use ArticleService;

/**
 * Created by PhpStorm.
 * User: Ray
 * Date: 17/6/5
 * Time: 上午10:52
 */
class HomeRepository
{
    public function insert($params)
    {
        return ArticleService::insert($params);
    }

    public function getById($id)
    {
        return ArticleService::getById($id);
    }
}