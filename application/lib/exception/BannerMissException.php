<?php
/**
 *  Filename    BannerMissException.php
 *  Creator     frankie
 *  CreateTime  0:25
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code        = 404;
    public $errorCode   = 40000;
    public $msg         = "Sorry喔, 没有找到您想找的Banner信息!";
}