<?php


namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
    # 设定传递所有数据的规则
    protected $rule = [
        'products'  => 'checkProducts'
    ];

    # 设定单一数据的规则
    protected $singleRule = [
        'product_id'    => 'require|isPostiveInteger',
        'count'         => 'require|isPostiveInteger',
    ];

    protected function checkProducts($values)
    {
        if(!is_array($values)){
            throw new ParameterException(['msg'=>'商品参数不正确']);
        }
        if(empty($values)){
            throw new ParameterException(['msg'=>'商品列表不能为空']);
        }
        foreach($values as $value){
            $this->checkProduct($value);
        }
        return true;
    }

    protected function checkProduct($value)
    {
        # 手动调用自定义验证规则
        $validate   = new BaseValidate($this->singleRule);
        $result     = $validate->check($value);
        if(!$result){
            throw new ParameterException(['msg'=>'商品参数错误']);
        }
    }
}