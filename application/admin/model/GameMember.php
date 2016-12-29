<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/11/2
 * Time: 16:22
 */

namespace app\admin\model;


class GameMember extends Common
{
    public function _field($result)
    {
        foreach($result as $key => &$val){
            $val['member_name'] = '听风';
            $val['game_name'] = Game::where('id',$val['game_id'])->value('name');
        }
        return $result;
    }
    /**
     * 根据GameId查数据
     * @param $id
     * @return mixed
     */
    public function fromGameId($id){
        $result = $this->where('game_id', 'eq', $id)->select();
        return $result;
    }
}