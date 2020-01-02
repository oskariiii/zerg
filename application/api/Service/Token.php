<?php


namespace app\api\Service;


class Token
{
    public static function generateToken()
    {
        # 32位长度随机字符串 默认32,$int 返回字符串长度
        $randChars  = getRandChar();
        # 为了安全,三组字符串MD5加密
        $time = time();
        $timestamp  = $_SERVER['REQUEST_TIME'];
        # salt 盐   config('文件名.键名称')
        $salt   = config('secure.token_salt');
        return md5($randChars.$timestamp.$salt);
    }
}