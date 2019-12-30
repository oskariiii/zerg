<?php

namespace app\api\model;

class Theme extends BaseModel
{
    # 设置隐藏字段
    protected $hidden = [
        'delete_time',
        'update_time',
        'topic_img_id',
        'head_img_id'
    ];
    # 一对一 获取 topic 图片信息
    public function topicImg()
    {
        return $this->belongsTo('Image','topic_img_id','id');
    }
    # 一对一 获取
    public function headImg()
    {
        return $this->belongsTo('Image','head_img_id','id');
    }
    # 多对多获取products信息
    public function products()
    {
        # 4个参数,(关联模型,两个模型的中间表,中间表中关联模型的id,中间表中本模型的id)
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public static function getThemeWithProduct($id)
    {
        return self::with('products,topicImg,headImg')->find($id);
    }
}
