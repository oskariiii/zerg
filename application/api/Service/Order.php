<?php


namespace app\api\Service;

use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\Order as OrderModel;
use app\api\model\UserAddress;
use app\lib\exception\UserException;
use app\lib\exception\OrderException;
use think\Exception;
use think\Db;

/**
 * Class Order 订单业务逻辑
 * @package app\api\Service
 */
class Order
{
    # 订单商品列表, 客户端传入的products信息
    protected $oProducts;
    # 根据product_id 从数据库中查询出来的实际数据,包括库存量
    protected $Products;
    protected $uid;

    /**
     * 下单
     * @param $uid       用户id
     * @param $oProducts 传入的商品信息
     * @return array
     */
    public function place($uid,$oProducts)
    {
        # oProducts 与 products 做对比
        # products 从数据库中查询出来
        $this->oProducts = $oProducts;
        $this->Products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if(!$status['pass']){
            $status['order_id'] = -1;
            return $status;
        }
        # 创建订单/快照
        $orderSnap = $this->snapOrder($status);
        # 生成订单,录入数据库.
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    /**
     * @param $snap 订单快照信息
     * @return array
     */
    private function createOrder($snap)
    {
        # 开启事务,操作完成提交事务操作; 操作失败, 使用rollback回滚操作
        Db::startTrans();
        try{
            # 生成订单号
            $orderNo = $this->makeOrderNo();
            $order = new OrderModel();
            # 订单参数赋值
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            if(!$order->save()){
                throw new OrderException(['msg' => '订单创建失败, 请重试!']);
            }
            $orderID= $order->id;
            $create_time = $order->create_time;
            # 使用 & 引用符号, 修改数组内的值
            foreach ($this->oProducts as &$p)
            {
                # 订单生成后, 将快照信息内的订单号修改为生成订单的订单号
                $p['order_id']  = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts); # 这里调用saveALL方法是因为 $this->oProducts 为数组, 多组数据

            # 结束事务
            Db::commit();
            return [
                'order_no'      => $orderNo,
                'order_id'      => $orderID,
                'create_time'   => $create_time,
            ];
        }catch (Exception $ex)
        {
            # 回滚
            Db::rollback();
            throw $ex;
        }
    }

    public static function makeOrderNo()
    {
        $yCode = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $orderSN = $yCode[intval(date("Y")) - 2020] . strtoupper(dechex(date("m"))).date("d") . substr(time(),-5) . substr(microtime(),2,5) . sprintf('%02d', rand(0, 99));
        return $orderSN;
    }

    /**
     * @param $status 生成订单快照所需要的订单信息
     * @return array
     */
    public function snapOrder($status)
    {
        $snap = [
            'orderPrice'        => 0,   #
            'totalCount'        => 0,   #
            'pStatus'           => [],  # 快照中单商品信息
            'snapAddress'       => '',  # 快照中的收货地址
            'snapImg'           => '',  # 商品交易时的图片
        ];
        $snap['orderPrice']     = $status['orderPrice'];
        $snap['totalCount']     = $status['totalCount'];
        $snap['pStatus']        = $status['pStatusArray'];
        $snap['snapAddress']    = json_encode($this->getOrderStatus(),JSON_UNESCAPED_UNICODE);
        # 将第一件产品作为缩略信息
        $snap['snapName']       = $this->Products[0]['name'];
        $snap['snapImg']        = $this->Products[0]['main_img_url'];
        /*
        if(count($this->Products) > 3 ){
            $snap['snapName'] .= "等";
        }
        */
        # 上述判断另外一种写法
        $snap['snapName']       = count($this->Products) > 3 ? $snap['snapName'] .= "等" : $snap['snapName'];
        return $snap;
    }

    /**
     * 获取用户地址
     * @param $userAddress object
     * @return array
     */
    private function getUserAddress()
    {
        $userAddress = UserAddress::where('uid','=',$this->uid)->find(); # object
        if(!$userAddress){
            throw new UserException(['msg' => '用户收货地址不存在,订单创建失败!','errorCode' => 60001]);
        }
        return $userAddress->toArray();
    }

    public function getOrderStatus()
    {
        $status = [
            'pass'          => true,
            'orderPrice'    => 0,
            'totalCount'    => 0,
            'pStatusArray'  => [ # 保存所有商品信息.
            ],
        ];
        # 循环遍历做数据库库存量对比
        foreach ($this->oProducts as $oProduct){
            $pStatus = $this->getProductStatus($oProduct['product_id'],$oProduct['count'],$this->Products);
            if(!$pStatus['haveStock']){
                $status['pass'] = false;
            }
            $status['orderPrice']   += $pStatus['totalPrice'];
            $status['totalCount']   += $pStatus['count'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }

    /**
     * @param $oPID          订单中的某一商品id
     * @param $oCount        当前订单某一物品的请求数量
     * @param $products      实际库存量
     * @return array
     */
    private function getProductStatus($oPID,$oCount,$products)
    {
        #  pStatus 保存订单里某一个商品的详细信息
        $pStatus = [
            'id'        => null,
            'haveStock' => false,
            'count'     => 0,
            'name'      => '', # 商品名
            'totalPrice'=> 0, # 某一类商品单价 x 该商品的购买的数量
        ];
        $pIndex = -1;
        for($i=0;$i<count($products);$i++){
            # 获取对应下标值
            if($oPID == $products[$i]['id']){
                $pIndex = $i;
            }
        }
        if($pIndex == -1){
            throw new OrderException([
                'msg'   => 'ID为 '.$oPID.' 的商品不存在, 订单创建失败!'
            ]);
        }else{
            $product = $products[$pIndex];
            $pStatus['id']          = $product['id'];
            $pStatus['name']        = $product['name'];
            $pStatus['count']       = $oCount;
            $pStatus['totalPrice']  = $product['price'] * $oCount;
            if( $product['stock'] - $oCount >= 0 ){
                $pStatus['haveStock']   = true;
            }
        }
        return $pStatus;
    }
    
    /**
     * 根据订单查询数据库中对应的产品信息
     */
    private function getProductsByOrder($oProducts)
    {
        # 循环获取商品id
        $oPIDS = array();
        foreach ($oProducts as $value) {
            array_push($oPIDS, $value['product_id']);
        }
        $products = Product::all($oPIDS)
                # ->visible(['id','price','stock','name','main_img_url'])
                ->toArray();
        return $products;
    }
}