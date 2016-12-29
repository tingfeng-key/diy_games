<?php
namespace app\admin\controller;

use core\input;

class Index extends Common
{
    public function index()
    {
        $node = model('SysNode')->getMenu();
        return $this->fetch('index', [
            'node' => $node,
        ]);
    }

    public function login(){
        return $this->fetch();
    }

    /**
     * 检测登录页面提交的信息
     */
    public function checkLogin(){
        $username = input('?post.username')?input('post.username'):'';
        $password = input('?post.password')?input('post.password'):'';
        $validate = $this->validate(
            [
                'username'  => $username,
                'password' => $password,
            ],
            [
                'username'  => 'require|max:8',
                'password' => 'require'
            ]
        );
        if (true !== $validate) {
            return $this->error($validate);
        }

        $user = model('SysUser');
        $userData = $user->userLoginCheck($username, $password);
        if(is_null($userData)){
            $this->error('用户名或密码错误');
        }
        session(config('user_key'), $userData);
        if(input('?post.isCookie') && (input('post.isCookie') == 'on')){
            cookie(config('user_key'), $userData);
        }
        $this->success('登录成功', Url(config('default_index')));
    }
}
