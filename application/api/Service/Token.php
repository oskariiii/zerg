<?php


namespace app\api\Service;


use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        # 32位长度随机字符串 默认32,$int 返回字符串长度
        $randChars = getRandChar();
        # 为了安全,三组字符串MD5加密
        $time = time();
        $timestamp = $_SERVER['REQUEST_TIME'];
        # salt 盐   config('文件名.键名称')
        $salt = config('secure.token_salt');
        return md5($randChars . $timestamp . $salt);
    }

    /**
     * 通用方法: 通过传值获取缓存中想要的变量
     * @param $key mixed 缓存中的键值
     * @return mixed 返回缓存中key对应的值
     */
    public function getCurrentTokenVar($key)
    {
        # 获取请求头部信息
        $token = Request::instance()->header('token');
        # 读取缓存中对应的变量值
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }else{
            # 获取到的是 string 数据, 转成数组, 方便后续处理.
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($vars,$key)){
                return $vars[$key];
            }else{
                throw new Exception("尝试获取的Token变量并不存在!");
            }
        }
    }

    /**
     * 获取当前用户uid
     */
    public static function getCurrentUid()
    {
        # 获取缓存中的令牌, 通过令牌查询用户的uid
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

}