<?php
/**
 *  Filename    ThemeException.php
 *  Creator     frankie
 *  CreateTime  22:27
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code        = 404;
    public $errorCode   = 30000;
    public $msg         = "Sorry喔, 没有找到您想找的Theme主题信息!";
}