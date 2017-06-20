<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $tpl = null;

    //格式化输出
    protected function formatOutput($result = [])
    {
        $result['user'] = $this->user();

        return [
            'code' => config('custom_code.success.code'),
            'msg' => '成功',
            'info' => $result
        ];
    }

    protected function setTpl($tpl)
    {
        $this->tpl = $tpl;
    }

    protected function tplOutput($result = [])
    {
        $result['user'] = $this->user();

        return [
            'tpl' => $this->tpl,
            'data' => $result,
        ];
    }

    public function user()
    {
        return empty(Auth::user()) ? null : [
            'id' => object_get(Auth::user(), 'id', 0),
            'name' => object_get(Auth::user(), 'name', ''),
            'mobile' => object_get(Auth::user(), 'mobile', ''),
        ];
    }
}
