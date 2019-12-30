<?php
/**
 *  Filename    ParameterException.php
 *  Creator     frankie
 *  CreateTime  0:27
 */

namespace app\lib\exception;


use Throwable;

class ParameterException extends BaseException
{
    public $code        = 400;
    public $errorCode   = 10000;
    public $msg         = "Sorry喔, 您提供的参数有误!";
}