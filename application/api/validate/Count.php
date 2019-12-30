<?php
/**
 *  Filename    Count.php
 *  Creator     frankie
 *  CreateTime  23:32
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPostiveInteger|between:1,10'
    ];

    protected $message = [ 'count' => 'count只能是 1 - 10 之间的正整数'];
}