<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    'list' => 'game/Index/index',
    'make/:id' => 'game/Index/make',
    'buy/:id' => 'game/Index/buy',
    'start/:id' => 'game/Index/start',
    'megame' => 'game/Member/mygame',
    'deleteMyGame' => 'game/Member/delete_my_game',
    'pay_callback' => 'game/Index/pay_callback',
    'getGameConfig/:id' => 'game/Index/game_get_config/',
];
