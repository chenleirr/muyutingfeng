<?php

namespace App\Http\Requests;

class JsonRequest extends Request
{
    public function rules()
    {
        return [
            'prj_name' => 'sometimes|string',
            'module1_key' => 'sometimes|string',
            'module1_val' => 'sometimes|string',
            'module2_key' => 'sometimes|string',
            'module2_val' => 'sometimes|string',
            'module3_key' => 'sometimes|string',
            'module3_val' => 'sometimes|string',

        ];
    }

    public function messages()
    {
        return [
            'mobile.is_mobile' => '手机号格式错误',
        ];
    }
}