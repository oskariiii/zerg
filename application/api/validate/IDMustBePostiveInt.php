<?php
/**
 *  Filename    IDMustBePostiveInt.php
 *  Creator     frankie
 *  CreateTime  0:36
 */

namespace app\api\validate;


class IDMustBePostiveInt extends BaseValidate
{
    protected $rule =   [
        'id'    => 'require|isPostiveInteger',
        # 'num'   => 'in:1,2,3',  # 访问url /banner/0.1?num=4 查看错误信息
    ];

    protected $message = [
        'id'    =>  'ID必须为正整数'
    ];
}