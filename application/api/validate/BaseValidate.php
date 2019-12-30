<?php
/**
 *  Filename    BaseValidate.php
 *  Creator     frankie
 *  CreateTime  0:32
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        # 获取所有HTTP传入的参数
        $request    = Request::instance();
        $param      = $request->param();
        # 校验数据
        $result     = $this->batch()->check($param);
        # 判断数据校验结果并返回给客户端
        if(!$result){
            # 这里使用的是TP默认的全局异常类, 返回的是全局异常类默认信息
            # $error  = $this->error;
            # throw new Exception($error);
            # 使用ParameterException 自定义类, 抛出自定义错误信息
            $e  = new ParameterException([
                # 'code'      =>  '401',
                # 'errorCode' =>  '10002',
                'msg'       =>  $this->error
            ]);
            # 复写异常信息, 此处使用修改对象值, 可以使用对象内部构造函数来写默认值
            # $e->msg = $this->error;
            throw $e;

        }else{
            return true;
        }
    }

    /**
     * @url   /banner/:id
     * @param string $value 传入的需要验证的数据
     * @param string $rule
     * @param string $data  所有数据
     * @param string $field 校验的字段
     * @return bool|string  返回结果
     */
    protected function isPostiveInteger($value,$rule='',$data='',$field=''){
        if(is_numeric($value) && is_int($value + 0) && ($value +0) >0){
            return true;
        }else{
            # return $field."必须是正整数!";
            return false;
        }
    }

    protected function isNotEmpty($value,$rule='',$data='',$field='')
    {
        if(empty($value)){
            return $field."不能为空";
        }else{
            return true;
        }
    }
}