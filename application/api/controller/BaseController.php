<?php


namespace app\api\controller;


use app\api\Service\Token as TokenService;
use think\Controller;

class BaseController extends Controller
{

    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }
}