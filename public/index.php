<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
# 定义日志记录文件存储路径
define('LOG_PATH', __DIR__ . '/../application/log/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
# 初始化SQL日志配置
\think\log::init([
    'type'  => 'File',
    'path'  =>  LOG_PATH,
    'level' => ['sql'],
]);