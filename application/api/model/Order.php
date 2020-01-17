<?php


namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id','update_time','delete_time'];
    protected $autoWriteTimestamp = true; # 开启 create_time update_time delete_time 自动填入
    # 自定义时间戳写入字段
    # protected $createTime = 'create_timestamp';
}