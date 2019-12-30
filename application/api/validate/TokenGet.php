<?php
/**
 *  Filename    TokenGet.php
 *  Creator     frankie
 *  CreateTime  22:15
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code'  => 'require|isNotEmpty',
    ];

    protected $message = [
        'code'   => '参数错误! 需要提供校验值[code]'
    ];
}