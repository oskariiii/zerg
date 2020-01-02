<?php
/**
 *  Filename    UserToken.php
 *  Creator     frankie
 *  CreateTime  22:25
 */

namespace app\api\Service;


use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;

class UserToken extends Token
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
                return $this->grantToken($wxReult);
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
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        $cacheValue = $this->prepareCacheValue($wxReult,$uid);
        $token = $this->saveToCache($cacheValue);
        return $token;
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

    /**
     * @param string $openid 微信服务器返回的openid字符串
     * @return mixed 创建用户后, 返回信息ID
     */
    private function newUser($openid)
    {
        $user = UserModel::create(['openid'=>$openid]);
        return $user->id;
    }

    /**
     * @param array $wxResult 微信服务器返回的带有openid的数据
     * @param string $uid   查询/创建用户返回的用户id
     * @return array
     */
    private function prepareCacheValue($wxResult,$uid)
    {
        $cacheValue = $wxResult;
        $cacheValue['uid']  = $uid;
        $cacheValue['scope']    = 16; # 权限控制
        return $cacheValue;
    }

    /**
     * 存储用户uid以及openid等信息
     * @param array $cacheValue
     * @return boolean
     */
    private function saveToCache($cacheValue)
    {
        $key    = self::generateToken();
        $value  = json_encode($cacheValue);
        $expire_in = config('setting.token_expire_in'); # 获取设定的令牌(即缓存)的过期时间
        $request = cache($key,$value,$expire_in); # TP5的内置缓存使用方法 cache(键,值,过期时间)
        if(!$request){
            throw new TokenException([
                'msg'       => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }
}