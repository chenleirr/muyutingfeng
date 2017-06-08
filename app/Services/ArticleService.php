<?php

namespace App\Services;

use App\Models\ArticleModel;
use App\Repositories\ModelRepository;
use App\Exceptions\CustomException;

class ArticleService extends ModelRepository
{
    public function __construct(ArticleModel $articleModel)
    {
        $this->model = $articleModel;
    }

    public function insert($data)
    {
        $this->model->fill(array_only($data, [
            'title',
            'content',
            'read',
            'group',
            'status',
        ]));
        $this->model->save();

        return $this->model;
    }

    public function getById($id)
    {
        if (empty($id) && !is_int($id)) {
            throw new CustomException('需要数字类型的id');
        }
        return $this->model->find($id);
    }
}