<?php


namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code        = 404;
    public $errorCode   = 999;
    public $msg         = "微信服务器借口调用失败,请重试!";
}