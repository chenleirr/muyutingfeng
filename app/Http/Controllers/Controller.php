<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //格式化输出
    protected function formatOutput($result)
    {
        return [
            'code' => config('custom_code.success.code'),
            'msg' => '成功',
            'info' => $result
        ];
    }
}
