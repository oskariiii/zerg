<?php
/**
 *  Filename    User.php
 *  Creator     frankie
 *  CreateTime  22:36
 */

namespace app\api\model;


class User extends BaseModel
{

    /**
     * user 关联属性  address
     */
    public function address()
    {
        return $this->hasOne('UserAddress','user_id','id');
    }

    public static function getByOpenID($openid)
    {
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }
}