<?php


namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id','update_time','delete_time'];
}