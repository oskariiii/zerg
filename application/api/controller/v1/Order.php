<?php


namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\OrderPlace;
use app\api\Service\Token as TokenService;
use app\api\Service\Order as OrderService;

class Order extends BaseController
{
    # 1. 用户提交选择的商品后,向API提供所选择的产品的相关信息
    # 2. API收到信息后, 需要检查订单相关库存量
    # 3. 有库存, 订单数据存入数据库中 ( 下单成功 ), 返回客户端消息, 可以支付
    # 4. 调用支付接口进行支付
    # 5. 还需要再次进行库存检测
    # 6. 调用微信支付接口进行支付
    # 7. 处理微信返回数据,根据数据进行判断支付状态是否成功.
    # 8. 成功 检测库存量 允许 库存量-1; 失败 返回失败结果

    /**
     * 发起订单前置操作
     * 排除管理员, 只允许管理员以外的用户权限进行该接口访问
     */
    protected $beforeActionList = [
        # example
        # 表示 second third 方法在执行前会执行first方法
        # 'first' => ['only' => 'second,third']
        'checkExclusiveScope' => ['only'=>'placeOrder']
    ];
    /**
     * 发起订单
     * @url api/v1/order
     */
    public function placeOrder()
    {
        # 参数校验,定义接口获取参数变量
        (new OrderPlace())->goCheck();
        # 接收数据  因为这里为数组参数, 所以需要添加  /a  获取数组数据
        $products = input('post.products/a');
        # 获取用于uid
        $uid    = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid,$products);
        return $status;
    }
}