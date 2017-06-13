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
            'title_pic',
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

    public function getList($params)
    {
        $params = array_only($params, [
            'group',
            'status'
        ]);
        $page = array_get($params, 'page', 1);
        $perPage = array_get($params, 'perpage', 10);
        $condition = [];
        foreach ($params as $key => $value) {
            array_push($condition, [$key, '=', $value]);
        }
        $query = $this->buildQuery($condition);
        $result = $this->buildList($query, $page, $perPage);

        return $result;
    }
}