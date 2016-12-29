<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/10/24
 * Time: 11:59
 */

namespace app\game\model;


class Game extends Common
{
    /**
     * 获取全部内容
     * @param $map
     * @return mixed
     */
    public function getAll($map){
        try{
            $this->alias('g')
                ->join('__TYPE__ t', 'g.type_id = t.id');
            //名称搜索
            if(!empty($map['name'])){
                $this->where('g.name','like','%'.$map['name'].'%');
            }
            //根据类型搜索
            if(!empty($map['type'])){
                $this->where('g.type_id','eq',$map['type']);
            }
            if(!empty($map['pageSize']) && !empty($map['page'])){
                $page = $map['page'];
                $pageSize = $map['pageSize'];
                $start = intval($page*$pageSize);
                $limit = (int)$pageSize;
                $this->limit($start, $limit);
            }
            $result = $this->field([
                    'g.*',
                    't.name' => 'type_name'
                ])->select();
            return $result;
        }catch (\Exception $e){
            dump($e->getMessage());
        }

    }

    /**
     * 获取游戏价格
     * @param $id
     * @return bool|mixed
     */
    public function getPrice($id){
        $id = (int)$id;
        $price = $this->where('id', 'eq', $id)->value('price');
        $result = (is_null($price))?false:$price;
        return $result;
    }
}