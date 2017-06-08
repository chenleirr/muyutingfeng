<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CustomException;
use Config;
use Validator;

abstract class Request extends FormRequest
{

    public function __construct()
    {
        Validator::extend('is_mobile', function ($attribute, $value, $parameters) {
            $phonePattern = '/^(1\d{10})$/';
            return preg_match($phonePattern, $value);
        });
        Validator::extend('string_or_array', function ($attribute, $value, $parameters) {
            return is_string($value) || is_array($value);
        });

        // 备用电话
        Validator::extend('is_reserve_mobile_number', function ($attribute, $value, $parameters) {
            $numbers = explode(',', $value);
            $phonePattern = '/^(1\d{10}|\d{8})$/';
            foreach ($numbers as $each) {
                if (!preg_match($phonePattern, $each)) {
                    return false;
                }
            }
            return true;
        });

        // 客服电话
        Validator::extend('is_stel', function ($attribute, $value, $parameters) {
            $phonePattern = '/^\d{6,12}$/';
            return preg_match($phonePattern, $value);
        });

        // 邮编
        Validator::extend('is_post_code', function ($attribute, $value, $parameters) {
            $phonePattern = '/^\d{6}$/';
            return preg_match($phonePattern, $value);
        });

        //  是否utf8mb4格式(数据库不支持utf8mb4)
        Validator::extend('is_no_utf8mb4_char', function ($attribute, $value, $parameters) {
            if (!is_string($value)) {
                return true;
            }
            $len = strlen($value);
            for ($i = 0; $i < $len; $i++) {
                $c = ord($value[$i]);
                if ($c > 239) {
                    return false;
                }
            }
            return true;
        });

        Validator::extend('is_citizen_id', function ($attribute, $value, $parameters) {
            $citizenidRegex = '/(^(\d{6})(18|19|20)(\d{2})((0[1-9])|(1[0-2]))(([0|1|2][0-9])|(3[0-1]))(\d{3})(\d|X|x){1}$)/';

            return preg_match($citizenidRegex, $value);
        });

        Validator::extend('is_chinese', function ($attribute, $value, $parameters) {
            if (!is_string($value)) {
                return false;
            }
            $mLen = mb_strlen($value, 'utf-8');

            $sLen = strlen($value);
            // 中文编码长度是字符串3倍
            return ($sLen / $mLen == 3) && ($sLen % 3 ==0);
        });

        Validator::extend('str_between', function ($attribute, $value, $parameters) {
            return mb_strlen($value) >= $parameters[0] && mb_strlen($value) <= $parameters[1];
        });

        // 是否物品发放允许物品类型
        Validator::extend('is_driver_item_distribute_item_type', function ($attribute, $value, $parameters) {
            $itemTypesConfig = config('driver.item_distribute_item_type', array());
            $itemTypes = array_pluck($itemTypesConfig, 'code');
            $array = is_array($value) ? $value : [$value];

            foreach ($array as $item) {
                if (!in_array($item, $itemTypes)) {
                    return false;
                }
            }

            return true;
        });

        // 是否物品发放允许操作类型
        Validator::extend('is_driver_item_distribute_operate_type', function ($attribute, $value, $parameters) {
            $operateTypesConfig = config('driver.item_distribute_operate_type', array());
            $operateTypes = array_pluck($operateTypesConfig, 'code');
            return in_array($value, $operateTypes);
        });

        // 是否司机邀请已打款操作类型
        Validator::extend('is_bones_paid_operate_type', function ($attribute, $value, $parameters) {
            $operateTypesConfig = config('driver_invite.bones_paid_operate_type', array());
            $operateTypes = array_pluck($operateTypesConfig, 'code');
            return in_array($value, $operateTypes);
        });

        // 车牌号验证
        Validator::extend('is_car_num', function ($attribute, $value, $parameters) {
            $carNumRegex = "/^[\x{4e00}-\x{9fa5}]{1}(?![A-Z]+$)(?![0-9]+$)[A-Z0-9]{6,7}$/u";

            return preg_match($carNumRegex, $value);
        });

        // 是否时间在今天之后
        Validator::extend('time_not_in_future', function ($attribute, $value, $parameters) {

            return strtotime($value) < time();
        });

        //检查数组中的元素是否都为正整数
        Validator::extend('integer_array', function ($attribute, $value, $parameters) {
            return is_array($value) && ctype_digit(implode('', $value));
        });

        Validator::extend('is_valid_name', function ($attribute, $value, $parameters) {
            $nameRegex = '/^[\x{4e00}-\x{9fa5}]{1,5}$/u';

            return preg_match($nameRegex, $value);
        });

        //合法的公司名称：支持汉字、中英文括号、英文字母，长度大于5
        Validator::extend('is_valid_company_name', function ($attribute, $value, $parameters) {
            $pattern = '/^[\x{4e00}-\x{9fa5}a-zA-Z()（）]{6,}$/u';
            return preg_match($pattern, $value);
        });

        //合法的营业执照号，仅支持数字和字母，长度15位或者18位
        Validator::extend('is_valid_business_license', function ($attribute, $value, $parameters) {
            $pattern = '/^([a-zA-Z0-9]{15})$|^([a-zA-Z0-9]{18})$/';
            return preg_match($pattern, $value);
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function response(array $errors)
    {
        $errorCode = config('custom_code.params_invalid.code');
        throw new CustomException(head($errors)[0], $errorCode, null, 'warning');
    }
}
