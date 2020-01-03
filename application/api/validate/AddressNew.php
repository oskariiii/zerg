<?php


namespace app\api\validate;


class AddressNew extends BaseValidate
{
    # 传递值的校验
    protected $rule = [
        'name'      => 'require|isNotEmpty',
        'mobile'    => 'require|isMobile',
        'province'  => 'require|isNotEmpty',
        'city'      => 'require|isNotEmpty',
        'country'   => 'require|isNotEmpty',
        'detail'    => 'require|isNotEmpty'
    ];
}