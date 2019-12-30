<?php
/**
 *  Filename    ProductExceptioin.php
 *  Creator     frankie
 *  CreateTime  23:41
 */

namespace app\lib\exception;


class ProductExceptioin extends BaseException
{
    public $code        = 404;
    public $errorCode   = 20000;
    public $msg         = "Sorry喔, 没有找到您指定的Product信息,请查看!";
}