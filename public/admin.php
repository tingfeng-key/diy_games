<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/10/26
 * Time: 14:33
 */
// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('BIND_MODULE','admin');
define('ROUTE_ROOT', '/diy_games/public/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';