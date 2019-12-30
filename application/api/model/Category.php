<?php
/**
 *  Filename    Category.php
 *  Creator     frankie
 *  CreateTime  23:22
 */

namespace app\api\model;


class Category extends BaseModel
{
    # 设置隐藏数据
    protected $hidden = [
        'create_time',
        'update_time',
        'delete_time',
    ];
    # 关联 image表
    public function img()
    {
        # 一对一关联image表
        return $this->belongsTo('Image','topic_img_id','id');
    }
}