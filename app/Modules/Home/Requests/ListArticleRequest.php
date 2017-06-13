<?php

namespace App\Modules\Home\Requests;

use App\Http\Requests\Request;

class ListArticleRequest extends Request
{
    public function rules()
    {
        return [
            'group' => 'sometimes|integer',
            'page' => 'sometimes|integer'
        ];
    }

    public function messages()
    {
        return [
            'integer' => ':attribute类型错误',
        ];
    }
}