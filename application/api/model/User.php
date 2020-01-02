<?php
/**
 *  Filename    User.php
 *  Creator     frankie
 *  CreateTime  22:36
 */

namespace app\api\model;


class User extends BaseModel
{
    public static function getByOpenID($openid)
    {
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }
}