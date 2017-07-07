<?php

namespace App\Http\Controllers;

include app_path('Libs/AliyunSdk/Sms/aliyun-php-sdk-core/Config.php');
use App\Exceptions\CustomException;
use App\Http\Requests\SendSmsRequest;
use RequestApi;
use Illuminate\Support\Str;
use AliyunSdkSms\Profile\DefaultProfile;
use AliyunSdkSms\DefaultAcsClient;
use App\Http\Requests\AliyunSmsRequest as AliRequest;
use Redis;

class SendSmsController extends Controller
{
    /**
     * 这是通过 网易云 发送验证码,但是已经放弃了。因为一次要冲 4000条短信才给用
     *
     * @param SendSmsRequest $request
     * @return array
     */
    public function send(SendSmsRequest $request)
    {
        $mobile = $request->get('mobile');
        $url = 'https://api.netease.im/sms/sendcode.action';
        $params = [
            'mobile' => $mobile,
            'templateid' => 3057746,
        ];
        $appKey = env('SMS_APP_KEY');
        $appSecret = env('SMS_APP_SECRET');
        $nonce = Str::random(16);
        $time = time();
        $checkSum = sha1('' . $appSecret . $nonce . $time);

        $options = [
            'headers' => [
                'AppKey' => $appKey,
                'Nonce' => $nonce,
                'CurTime' => $time,
                'CheckSum' => $checkSum,
                'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',

            ],
        ];
        throw new CustomException('用阿里大鱼发短信吧, 少年!');
        $info = RequestApi::postSmsCode($url, $params, $options);

        return $this->formatOutput($info);
    }

    /**
     * 还是使用阿里云SDK发送短信比较好。费用也很便宜, 冲点钱, 发一条口一条
     *
     * @param SendSmsRequest $request
     * @return array
     * @throws CustomException
     */
    public function sendByAliyun(SendSmsRequest $request)
    {
        $mobile = $request->get('mobile');
        $code = random_int(1000, 9999);
        $params = [
            'code' => $code
        ];
        $params = json_encode($params);
        //此处需要替换成自己的AK信息
        $accessKeyId = env('A_LI_ACCESS_KEY');
        $accessKeySecret = env('A_LI_ACCESS_SECRET');

        //短信API产品名
        $product = "Dysmsapi";
        //短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region
        $region = "cn-hangzhou";
        //初始化访问的acsCleint
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        $acsClient= new DefaultAcsClient($profile);
        $request = new AliRequest;
        //必填-短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为20个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        $request->setPhoneNumbers($mobile);
        //必填-短信签名
        $request->setSignName(env('IDENTIFY_CODE_SIGN'));
        //必填-短信模板Code
        $request->setTemplateCode(env('IDENTIFY_CODE_DEMO_CODE'));
        //选填-假如模板中存在变量需要替换则为必填(JSON格式)
        $request->setTemplateParam($params);
        //选填-发送短信流水号
        $request->setOutId("1234");
        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);

        Redis::setex('SmsCode' . $mobile, 600, $code);
        if (object_get($acsResponse, 'Code', '') !== 'OK') {
            throw new CustomException('发送短信失败!');
        }
        return $this->formatOutput();
    }
}