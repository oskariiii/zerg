<?php
/**
 *  Filename    BaseModel.php
 *  Creator     frankie
 *  CreateTime  0:50
 */

namespace app\api\model;


use app\lib\exception\ImageMissException;
use think\Model;

class BaseModel extends Model
{
    # 设置读取器 命名规则: get + 读取属性字段名称 + attr, 例如 获取name  getName
    # 读取器接收参数 $value
    /*public function getUrlAttr($value,$data)
    {
        # 拼接url完整地址, 获取img_prefix + 图片存储路径
        $imgUrl = $data['from'] == 1 ? config('setting.img_prefix').$value : $value;
        return $imgUrl;
    }*/

    # 换一种方法实现上述功能. 上面方法的缺陷在于, 并不是每个数据集中的Url字段都是代表着图片路径 不能一概拼接img_prefix
    protected function prefixImgUrl($value,$data)
    {
        # 拼接url完整地址, 获取img_prefix + 图片存储路径
        $imgUrl = $data['from'] == 1 ? config('setting.img_prefix').$value : $value;
        return $imgUrl;
    }
}