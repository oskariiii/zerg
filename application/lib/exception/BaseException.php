<?php
/**
 *  Filename    BaseException.php
 *  Creator     frankie
 *  CreateTime  0:21
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    # HTTP 状态码
    public $code = 400;
    # 自定义的错误码
    public $errorCode = '参数错误';
    # 错误的具体信息
    public $msg = 10000;

    /**
     * BaseException constructor.
     * @param array $param
     */
    public function __construct($param = [])
    {
        # 判断传入参数
        if(!is_array($param)){
            return ;
            # 如果强制要求传入参数为数组, 就使用下面的抛出异常;
            # 如果直接return 返回的是默认的成员变量
            # throw new Exception('传入的参数必须是数组');
        }
        # todo : 这里可以使用foreach循环处理
        # 教学视频中是判断传入的数组中是否存在键值, 存在即修改
        /*if(array_key_exists('code',$param)){
            $this->code = $param['code'];
        }*/
        # 这里是我的个人理解
        foreach ($param as $key => $value){
            $this->$key = $value;
        }
    }
}