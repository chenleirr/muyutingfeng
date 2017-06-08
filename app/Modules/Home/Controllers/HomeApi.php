<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Home\Requests\InsertArticleRequest;
use App\Exceptions\CustomException;
use App\Modules\Home\Repositories\HomeRepository;
use Illuminate\Http\Request;

class HomeApi extends Controller
{
    private $homeRepository;

    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function insert(InsertArticleRequest $request)
    {
        $key = $request->get('key');
        if ($key != env('ADD_ARTICLE_KEY', '')) {
            throw new CustomException('key值有误!');
        }
        $params = array_only($request->all(), [
            'title',
            'content',
        ]);
        $params['status'] = config('constants.article.status.normal.code');//正常
        $params['group'] = config('constants.group.other.dont_touch.code');//随笔

        $result = $this->homeRepository->insert($params);

        return $this->formatOutput([
            'id' => $result['id'],
        ]);
    }

    public function getById(Request $request)
    {
        $articleId = $request->get('id');
        if (empty($articleId) && !is_int($articleId)) {
            throw new CustomException('需要数字类型的id');
        }

        $result = $this->homeRepository->getById($articleId);
        return $this->formatOutput($result);
    }
}