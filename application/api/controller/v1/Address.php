<?php


namespace app\api\controller\v1;


use app\api\validate\AddressNew;
use app\api\Service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use think\Controller;
use app\lib\exception\ForbiddenException;

class Address extends Controller
{
    # tp5的前置操作 todo::将该方法提取到基类
    protected $beforeActionList = [
        # example
        # 表示 second third 方法在执行前会执行first方法
        # 'first' => ['only' => 'second,third']


        'checkPrimaryScope' => ['only'=>'createOrUpdateAddress']
    ];

    /**
     * 验证初级scope权限作用域
     */
    protected function checkPrimaryScope()
    {
        $scope = TokenService::getCurrentTokenVar('scope');
        if(!$scope){
            throw new TokenException();
        }else{
            if($scope >= ScopeEnum::User){
                return true;
            }else{
                # 抛出异常, 终止流程
                throw new ForbiddenException();
            }
        }
    }

    /**
     * 创建或者更新用户地址(一对一)
     * @url zerg.cn/api/v1/address
     * @return SuccessMessage
     */
    public function createOrUpdateAddress()
    {
        # 数据验证
        $validate = new AddressNew();
        $validate->goCheck();
        # 根据token获取uid
        # 根据uid查找用户数据,判断用户是否存在,如果不存在,返回错误信息. 
        # 获取用户从客户端传入的地址信息
        # 判断添加或更新地址
        # todo 目前地址与用于信息为一对一, 后期修改为一对多
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if(!$user){
            throw new UserException();
        }
        # 根据验证器获取参数变量   input('post.') 获取post方式提交的所有参数
        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if(!$userAddress){
            $user->address()->save($dataArray);
        }else{
            # 此处注意 更新 没有 () ****
            $user->address->save($dataArray);
        }
        # return $user;
        # 修改请求状态码
        # post提交数据, 返回状态码为201, 在返回信息时是200, 这里修改一下http请求状态码
        return json(new SuccessMessage(),201);
    }
    
}