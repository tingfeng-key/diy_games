<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/11/1
 * Time: 10:12
 */

namespace app\game\model;


class Member extends Common
{
    public function getAll($map){
        try{
            $this->alias('gm')
                ->join('__GAME__ g', 'gm.game_id = g.id')
                ->join('__TYPE__ t', 'g.type_id = t.id');
            /*//名称搜索
            if(!empty($map['name'])){
                $this->where('g.name','like','%'.$map['name'].'%');
            }
            //根据类型搜索
            if(!empty($map['type'])){
                $this->where('g.type_id','eq',$map['type']);
            }*/
            if(!empty($map['member_id'])){
                $this->where('gm.member_id','eq', (int)$map['member_id']);
            }
            if(!empty($map['pageSize']) && !empty($map['page'])){
                $page = $map['page'];
                $pageSize = $map['pageSize'];
                $start = intval($page*$pageSize);
                $limit = (int)$pageSize;
                $this->limit($start, $limit);
            }
            $result = $this->field([
                'gm.*',
                'g.logo' => 'g_logo',
                't.name' => 'type_name'
            ])->select();
            return $result;
        }catch (\Exception $e){
            dump($e->getMessage());
        }
    }

    /**
     * 获取单个游戏数据
     * @param $id
     * @param $field
     * @return mixed
     */
    public function findGame($id){
        $field = [
            'gm.*',
            'g.logo' => 'g_logo',
            'g.tpl_path' => 'tpl_path',
            'g.db_table' => 'g_db_table',
        ];
        $gm = $this->getGameMember($id, $field);
        if(is_null($gm)) return null;
        $gf = $this->getGameField(
            $gm['g_db_table'],
            $gm['game_attach_id']
        );
        if(is_null($gf)) return null;
        return array_merge($gf, $gm);
    }

    /**
     * 获取游戏用户信息
     * @param $id
     * @param $field
     * @return mixed
     */
    private function getGameMember($id, $field){
        $result = db('member')->alias('gm')
            ->join('__GAME__ g', 'gm.game_id = g.id && gm.id = '. $id)
            ->field($field)->find();
        return $result;
    }

    /**
     * 获取游戏附表信息
     * @param $name
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    private function getGameField($name, $id){
        $result = db('attach_'. $name)->where('id', $id)->find();
        return $result;
    }
}