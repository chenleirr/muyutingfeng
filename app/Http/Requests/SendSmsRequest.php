<?php

namespace App\Http\Requests;

class SendSmsRequest extends Request
{
    public function rules()
    {
        return [
            'mobile' => 'required|string|is_mobile',
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