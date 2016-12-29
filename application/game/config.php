<?php
//配置文件
return [
    'view_replace_str'  =>  [
        '__ROOT__' => ROUTE_ROOT,
        '__GAMES__' => ROUTE_ROOT.'games',
        '_COM_CSS_' => ROUTE_ROOT.'common/css',
        '_COM_JS_' => ROUTE_ROOT.'common/js',
        '__STATIC__' => ROUTE_ROOT.'game',
        '__CSS__' => ROUTE_ROOT.'game/css',
        '__FONTS__' => ROUTE_ROOT.'game/font',
        '__IMG__' => ROUTE_ROOT.'game/img',
        '__JS__' => ROUTE_ROOT.'game/js',
        '__ALT__' => '肥猪游戏',
    ],
    'jsonConfig' => [
        '_ROOT_' => ROUTE_ROOT,
        '_GAMES_' => ROUTE_ROOT.'games',
        'COM_CSS' => ROUTE_ROOT.'common/css',
        'COM_JS' => ROUTE_ROOT.'common/js',
        '_STATIC_' => ROUTE_ROOT.'game',
        '_CSS_' => ROUTE_ROOT.'game/css',
        '_FONTS_' => ROUTE_ROOT.'game/font',
        '_IMG_' => ROUTE_ROOT.'game/img',
        '_JS_' => ROUTE_ROOT.'game/js',
    ],
    'app_debug' => true,
    'login_url' => 'http://www.fz222.com/mlogin/index',
];