<?php

namespace app\api\controller\v1;

use app\api\validate\Count;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ProductExceptioin;
use think\Controller;
use think\Request;
use app\api\model\Product as ProductModel;

class Product extends Controller
{
    /**
     * 获取最新商品
     * @param $count int 获取最新商品数量
     * @return $products json 返回json格式的数据
     */
    public function getRecent($count = 10)
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRent($count);
        if(!$products){
            throw new ProductExceptioin();
        }
        return $products;
    }

    /**
     * @param $id int 接收的类目ID.
     * @return $product object 通过类目id 模型获取关联商品信息
     */
    public function getALlInCategory($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if($products->isEmpty()){
            throw new ProductExceptioin();
        }
        return $products;
    }

    /**
     * 获取某个商品的详情 包括参数以及多张图片
     * @param $id int 传入的商品id
     * @return $product json 返回的商品详情
     */
    public function getOne($id)
    {
        # 验证ID合法性, 正整数
        (new IDMustBePostiveInt())->goCheck();
        # 通过model获取商品详情
        $product = ProductModel::getProductDetail($id);
        if(!$product){
            throw new ProductExceptioin();
        }
        return $product;
    }
}
