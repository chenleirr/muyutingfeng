<?php

namespace App\Http\Requests;

class JsonRequest extends Request
{
    public function rules()
    {
        return [
            'prj_name' => 'required|string',
            'module1_key' => 'required|string',
            'module1_val' => 'required|string',
            'module2_key' => 'required|string',
            'module2_val' => 'required|string',
            'module3_key' => 'required|string',
            'module3_val' => 'required|string',

        ];
    }

    public function messages()
    {
        return [
            'mobile.required' => '请输入手机号',
            'mobile.is_mobile' => '手机号格式错误',
        ];
    }
}