<?php
/**
 *  Filename    Banner.php
 *  Creator     frankie
 *  CreateTime  0:16
 */

namespace app\api\controller\v1;


use app\api\validate\IDMustBePostiveInt;
use app\api\model\Banner as BannerModel;

class Banner
{
    /**
     * @url     /api/v1/banner/1
     * @param   $id
     * @return  json
     */
    public function getBanner($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $banner = BannerModel::getBannerByID($id);
        return $banner;
    }
}