<?php


namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = ['img_id','delete_time','product_id'];

    /**
     * 定义图片关系
     */
    public function imgUrl()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}