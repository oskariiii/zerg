<?php
/**
 *  Filename    IDCollection.php
 *  Creator     frankie
 *  CreateTime  15:44
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    # 定义验证规则
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    # 自定义报错信息
    protected $message = [
        'ids'   => 'ids 参数必须是以逗号分隔的一或多个正整数',
    ];

    /**
     * @param $value string 要验证的字符串
     * @return bool
     */
    protected function checkIDs($value)
    {
        # $value = $ids 的值
        if(empty($value))
        {
            return false;
        }
        $values = explode(',',$value);
        foreach ($values as $id){
            if(!$this->isPostiveInteger($id))
            {
                return false;
            }
        }
        return true;
    }
}