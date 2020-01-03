<?php

namespace app\api\model;

class Product extends BaseModel
{
    /**
     * @content string 设定隐藏字段
     * @param string pivot 中间表属性
     * @var array
     */
    protected $hidden = [
        'delete_time','update_time','from','pivot','category_id','create_time','main_img_id'
    ];

    # 设置读取器, 解决返回的theme信息中, main_img_url 不能显示完整路径问题
    public function getMainImgUrlAttr($value,$date)
    {
        return $this->prefixImgUrl($value,$date);
    }

    /**
     * 获取单个商品关联的详情图片 (一对多)
     */
    public function imgs()
    {
        return $this->hasMany('ProductImage','product_id','id');
    }
    /**
     * 获取单个商品关联的参数 (一对多)
     */
    public function properties()
    {
        return $this->hasMany('ProductProperty','product_id','id');
    }
    public static function getMostRent($count)
    {
        $products = self::limit($count)->order('create_time','DESC')->select(); # 这里的排序也可以写成  ( 'create_time DESC' )
        return $products;
    }

    /**
     * @param $CategoryId int 类目id
     * @return $products object 通过类目id获取所有关联的商品信息
     */
    public static function getProductsByCategoryID($CategoryId)
    {
        $products = self::where('category_id','=',$CategoryId)->select();
        return $products;
    }

    /**
     * 获取商品详情
     * @param $id int 传入的商品ID
     * @return $result json 返回json格式信息详情
     */
    public static function getProductDetail($id)
    {
        # $product = self::with('imgs,properties')->find($id); # 另外一种写法
        # $product = self::with(['imgs.imgUrl','properties'])->find($id); # 需要对 imgs.imgUrl 进行排序
        # 这里使用链式操作完成排序查询
        $product = self::with([
                'imgs'  => function($query){
                    $query->with(['imgUrl'])->order('order','asc');
                }
            ])
            ->with('properties')->find($id);
        return $product;
    }
}
