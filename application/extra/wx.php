<?php
/**
 *  Filename    wx.php
 *  Creator     frankie
 *  CreateTime  21:56
 */

return [
    # 微信接口需要传入的参数.
    'app_id'    => 'wxf21e8169868f0c0e',
    'app_secret'=> 'c0289db407f255a234aeb3b8c25603a0',
    # 该链接用 s% 作为占位符, 调取接口时动态拼接 appid, secret, jscode 拼接时注意次序
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?'."appid=%s&secret=%s&js_code=%s&grant_type=authorization_code"
];