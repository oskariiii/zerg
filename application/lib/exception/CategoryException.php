<?php
/**
 *  Filename    CategoryException.php
 *  Creator     frankie
 *  CreateTime  23:45
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code        = 404;
    public $errorCode   = 50000;
    public $msg         = "Sorry喔, 没有找到您想找的类目信息!";
}