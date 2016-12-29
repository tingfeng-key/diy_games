<?php
//配置文件
return [
    //用户sessionKey
    'user_key' => 'user',
    //公共方法
    'public_action' => ['Index/login', 'Index/checkLogin'],
    //后台默认首页
    'default_index' => 'index/index',
    //后台默认登录
    'default_login' => 'index/login',
    //模板配置
    'view_replace_str'  =>  [
        '__ROOT__' => ROUTE_ROOT,
        '_COM_CSS_' => ROUTE_ROOT.'common/css',
        '_COM_JS_' => ROUTE_ROOT.'common/js',
        '__STATIC__' => ROUTE_ROOT.'admin',
        '__CSS__' => ROUTE_ROOT.'admin/css',
        '__IMG__' => ROUTE_ROOT.'admin/img',
        '__JS__' => ROUTE_ROOT.'admin/js',
        '__UPLOADIFY__' => ROUTE_ROOT.'common/uploadify',
        '__UPLOADFILE__' => Url('File/upload', null, ''),
        '__ALT__' => '肥猪游戏',
    ]


];