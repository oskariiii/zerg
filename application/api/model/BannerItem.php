<?php
/**
 *  Filename    BannerItem.php
 *  Creator     frankie
 *  CreateTime  23:46
 */

namespace app\api\model;

class BannerItem extends BaseModel
{
    # 设置隐藏字段
    protected $hidden = ['id','img_id','banner_id','update_time','delete_time'];
    public function img()
    {
        # 一对一 调用
        # model:关联模型名称 foreignKey:本模型外键 localKey:关联模型主键
        return $this->belongsTo('Image','img_id','id');
    }
}