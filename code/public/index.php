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
// 绑定前台模块
define("BIND_MODULE","index");
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';


//  1. 绑定前
//  localhost/.../public/index.php/模块/控制器/方法
// 2. 绑定后
//  localhost/.../public/index.php/控制器/方法