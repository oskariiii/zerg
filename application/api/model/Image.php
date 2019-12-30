<?php
/**
 *  Filename    Image.php
 *  Creator     frankie
 *  CreateTime  0:37
 */

namespace app\api\model;


use app\lib\exception\ImageMissException;

class Image extends BaseModel
{
    # 设置隐藏字段
    protected $hidden = ['id','from','delete_time','update_time'];

    /**
     * @param $value
     * @param $data
     * @return string
     */
    public function getUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }
}