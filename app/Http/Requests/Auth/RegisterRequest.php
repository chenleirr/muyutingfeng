<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class RegisterRequest extends Request
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:10',
            'mobile' => 'required|string|min:11|max:11|unique:users',
            'email' => 'required|string|email|max:255',
            'check_code' => 'required|integer',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required',
            'min' => ':attribute 字段长度太短',
            'max' => ':attribute 字段长度超过限制',
            'email' => ':attribute 邮箱格式不对',
            'unique' => ':attribute 已注册',
            'confirmed' => '两次密码不一致',
        ];
    }
}