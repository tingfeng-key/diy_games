<?php
namespace app\admin\model;

use think\Model;

class SysUser extends Model
{
    /**
     * 用户登录检测及密码处理
     * @param $username 用户名
     * @param $password 密码
     * @return array|false|\PDOStatement|string|Model
     */
    public function userLoginCheck($username, $password){
        $password = md5(md5($password));
        $field = 'username,role_id,super,id,status';
        $result = $this->where([
            'username' => $username,
            'password' => $password,
        ])->field($field)
            ->find();
        return $result;
    }

}