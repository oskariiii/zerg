<?php


namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code        = 401;
    public $errorCode   = 10001;
    public $msg         = "Token已过期或Token无效";
}