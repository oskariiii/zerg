<?php


namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code        = 403;
    public $errorCode   = 10001;
    public $msg         = "权限不足!";
}