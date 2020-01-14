<?php


namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id','update_time','delete_time'];
    protected $autoWriteTimestamp = true;
    # 自定义时间戳写入字段
    # protected $createTime = 'create_timestamp';
}