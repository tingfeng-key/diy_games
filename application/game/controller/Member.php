<?php
/**
 * Created by PhpStorm.
 * User: tingfeng
 * Date: 2016/11/22
 * Time: 13:53
 */

namespace app\game\controller;
class Member extends Common
{
    private static $member_id = 0;
    public function _initialize()
    {
        parent::_initialize();
        if(!$this->isLogined()){
            //$this->error('您还没有登录，请先登录平台！', config('login_url'));
        }
        self::$member_id = $this->isLogined();
    }

    /**
     * 我的游戏管理列表
     * @return mixed|void
     */
    public function mygame(){
        $map = [
            'member_id' => self::$member_id,
        ];
        $data = model('GameMember')->getAll($map);
        //dump($data);
        $this->assign([
            'data'=>$data,
        ]);
        return (Request()->isAjax())?$this->result($data):$this->fetch();
    }

    /**
     * 用户删除游戏
     */
    public function delete_my_game(){
        if(!input('?param.id')) return ;
        $id = (int)input('param.id');
        $map = [
            'id' => $id,
            'member_id' => self::$member_id,
        ];
        $dbObj = db('game_member');
        /*$result = $dbObj->where($map)->delete();
        if(false === $result){
            $lastSql = $dbObj->getLastSql();
            trace('用户删除游戏失败，Sql：'.$lastSql,'info');
            return $this->result('删除失败');
        }*/
        return $this->result('删除成功');
    }
}