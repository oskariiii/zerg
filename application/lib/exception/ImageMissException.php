<?php
/**
 *  Filename    ImageMissException.php
 *  Creator     frankie
 *  CreateTime  0:43
 */

namespace app\lib\exception;


class ImageMissException extends BaseException
{
    public $code        = 404;
    public $errorCode   = 40000;
    public $msg         = "Sorry喔, 没有找到图片对应的Url地址!";
}