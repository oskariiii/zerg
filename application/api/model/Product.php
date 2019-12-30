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
}
