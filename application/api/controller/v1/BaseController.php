<?php


namespace app\api\controller\v1;


use think\Controller;

class BaseController extends Controller
{
    # tp5的前置操作
    protected $beforeActionList = [
        # example
        # 表示 second third 方法在执行前会执行first方法
        # 'first' => ['only' => 'second,third']
        'checkPrimaryScope' => ['only'=>'createOrUpdateAddress']
    ];
}