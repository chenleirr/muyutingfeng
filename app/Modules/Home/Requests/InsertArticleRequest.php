<?php

namespace App\Modules\Home\Requests;

use App\Http\Requests\Request;

class InsertArticleRequest extends Request
{
    public function rules()
    {
        return [
            'key' => 'required|string',

            'title' => 'required|string|min:1|max:50',
            'content' => 'required|string|min:1',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute不能为空',
            'max' => ':attribute长度超过最大限制',
            'min' => ':attribute长度太小',
            'integer' => ':attribute类型错误',
        ];
    }
}