<?php
/**
 *  Filename    Token.php
 *  Creator     frankie
 *  CreateTime  22:10
 */

namespace app\api\controller\v1;


use app\api\Service\UserToken;
use app\api\validate\TokenGet;

class Token
{
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();
        # 服务层 UserToken 使用了构造函数赋值成员变量,所以这里传入$code参数
        $ut = new UserToken($code);
        $token = $ut->get();
        return ['token' => $token];
    }
}