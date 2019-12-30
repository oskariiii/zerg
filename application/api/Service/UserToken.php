<?php
/**
 *  Filename    UserToken.php
 *  Creator     frankie
 *  CreateTime  22:25
 */

namespace app\api\Service;


use app\lib\exception\WeChatException;
use think\Exception;

class UserToken
{
    # 定义成员变量
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code)
    {
        # 成员变量赋值.
        $this->code     = $code;
        $this->wxAppID  = config('wx.app_id');
        $this->wxAppSecret  = config('wx.app_secret');
        $this->wxLoginUrl   = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get()
    {
        # 发送http请求 common.php
        $result     = curl_get($this->wxLoginUrl);
        # 使用json_decode true 转为数组
        $wxReult   = json_decode($result,true); # 失败返回: {"errcode":40125,"errmsg":"invalid appsecret, view more at http:\/\/t.cn\/RAEkdVq, hints: [ req_id: 5.OFZRLnRa-CJ6sWa ]"}
        if(empty($wxReult)){
            throw new Exception('获取session_key以及openID时异常, 微信内部错误');
        }else{
            # 通常在微信返回值中,http响应码通为200,所以无法使用是否为空完整判断
            # 判断返回值中是否存有errcode键值对, 如果有说明返回了错误信息, 做进一步处理.
            $loginfail  = array_key_exists('errcode',$wxReult);
            if($loginfail){
                # 调用异常处理 WeChatExceotion, 将返回值中的信息返回给前台
                $this->processLoginError($wxReult);
            }else{
                # 调取成功后 授权(颁发令牌)
                $this->grantToken($wxReult);
            }
        }
    }
    /**
     * 微信接口调取成功, 授权(颁发令牌)
     * @param $wxResult json 微信返回值
     * @return mixed 返回错误信息给用户
     */
    private function grantToken($wxReult)
    {
        # 获取openId
        # 查询数据库中是否存在该openId, 存在不处理,不存在新增一条user信息.
        # 生成令牌, 准备缓存数据, 写入缓存
        # 返回令牌至客户端

        $openid = $wxReult['openid'];
    }

    /**
     * 微信接口调用失败, 返回错误信息
     * @param $wxResult json 微信返回值
     * @return mixed 返回错误信息给用户
     */
    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            # 抛出微信返回值中的错误信息与错误码
            'msg'       => $wxResult['errmsg'],
            'errcode'   => $wxResult['errcode']
        ]);
    }
}