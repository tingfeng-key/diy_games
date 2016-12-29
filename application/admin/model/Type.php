<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/10/30
 * Time: 21:16
 */

namespace app\admin\model;


class Type extends Common
{
    /**
     * 获取全部数据
     * @param $orde 排序
     * @param $field field
     * @return mixed
     */
    public function getAll($order, $field){
        $result = $this->order($order)->field($field)->select();
        return $result;
    }
}