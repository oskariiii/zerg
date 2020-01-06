<?php


namespace app\api\controller\v1;


use app\api\validate\AddressNew;
use app\api\Service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\exception\SuccessException;

class Address
{
    /**
     * 创建或者更新用户地址(一对一)
     */
    public function createOrUpdateAddress()
    {
        # 数据验证
        (new AddressNew())->goCheck();
        # 根据token获取uid
        # 根据uid查找用户数据,判断用户是否存在,如果不存在,返回错误信息. 
        # 获取用户从客户端传入的地址信息
        # 判断添加或更新地址
        # todo 目前地址与用于信息为一对一, 后期修改为一对多
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if(!user){
            throw new UserException();
        }
        $dataArray = getDatas();
        $userAddress = $user->address;
        if(!$userAddress){
            $user->address()->save($dataArray);
        }else{
            # 此处注意 更新 没有 () ****
            $user->address->save($dataArray);
        }
        # return $user;
        return new SuccessException();
    }
    
}