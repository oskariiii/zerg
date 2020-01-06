<?php


namespace app\lib\exception;


class SuccessException extends BaseException
{
    public $code        = 201;
    public $errorCode   = 0;
    public $msg         = "ok!";
}