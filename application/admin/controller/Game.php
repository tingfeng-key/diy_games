<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/10/27
 * Time: 13:54
 */

namespace app\admin\controller;

use app\admin\model\GameType;

class Game extends Common
{
    /*
     * 编辑操作前置方法
     */
    public function _edit(){
        $order = ['sort'=> 'asc'];
        $typeList = model('Type')->getAll($order,'id,name');
        $this->assign('typeList', $typeList);
    }

    /**
     * 获取用户信息
     */
    public function userInfo(){
        if(Request()->isAjax()){
            $id = input('param.id');
            $map = $this->_CommonMap(Request()->param());
            if(method_exists($this, '_map')){
                $map = $this->_map($map);
            }
            $pageSize = $map['pageSize'];
            unset($map['pageSize']);
            unset($map['page']);
            $order = ['id'=> 'asc'];
            $map['game_id'] = $id;
            unset($map['id']);
            $data = model('GameMember')->pageData($map, $order, $pageSize);
            return $this->result($data, 1);
        }
        return $this->fetch();
    }

    public function tplSet(){
        if(!input('?param.id'))
            return false;
        $id = input('param.id');
        $data = model('GameTpl')->get($id);
        $this->assign('data', $data)->fetch();
    }
}