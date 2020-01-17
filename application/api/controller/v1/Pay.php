<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;

class Pay extends BaseController
{
    # 权限验证
    protected $beforeActionList = [
        'checkExclusiveScope' => [ 'only' => 'getPreOrder' ], # 权限要求: 只有用户可以调用此接口
    ];

    # 请求预订单信息 API调用微信服务器生成的订单信息
    public function getPreOrder()
    {
        # 权限控制, 只有用户可以访问

    }
}