<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Home\Requests\InsertArticleRequest;
use App\Modules\Home\Requests\ListArticleRequest;
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
            'title_pic',
            'content',
            'group'
        ]);
        $params['status'] = config('constants.article.status.normal.code');//正常

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

    public function getList(ListArticleRequest $request) {
        $params['status'] = config('constants.article.status.normal.code');
        $params['page'] = $request->get('page', 1);
        $params['perpage'] = 10;
        if (!empty($request->get('group'))) {
            $params['group'] = $request->get('group');
        }
        $result = $this->homeRepository->getList($params);
        return $this->formatOutput($result);
    }
}