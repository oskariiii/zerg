<?php


namespace app\api\Service;

use app\api\model\Product;

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
     * @param $uid 用户id
     * @param $oProducts 传入的商品信息
     */
    public function place($uid,$oProducts)
    {
        # oProducts 与 products 做对比
        # products 从数据库中查询出来
        $this->oProducts = $oProducts;
        $this->Products = ;
        $this->uid = $uid;
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
                ->visible(['id','price','stock','name','main_img_url'])
                ->toArray();
        return $products;
    }
}