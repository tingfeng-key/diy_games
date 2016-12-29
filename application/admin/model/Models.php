<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/10/28
 * Time: 9:57
 */

namespace app\admin\model;


class Models extends Common
{
    public function getList($map){
        if(method_exists($this, '_map')){
            $this->_map($map);
        }
        $result = $this->where($map)->order([
            'sort' => 'asc',
            'id'=> 'asc'
        ])->paginate(1);
        return $result;
    }
}