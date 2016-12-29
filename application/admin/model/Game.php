<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/10/30
 * Time: 21:13
 */

namespace app\admin\model;


class Game extends Common
{
    protected $auto = ['status'];
    protected $insert = ['create_time'];
    protected $update = ['update_time'];

    /**
     * 遍历数据处理
     * @param $result
     * @return mixed
     */
    public function _field($result)
    {
        $status = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
        foreach($result as $key => &$val){
            $val['status_name'] = $status[$val['status']];
            $gameType = Type::get($val['type_id']);
            $val['type_name'] = $gameType['name'];
        }
        return $result;
    }

    /**
     * 获取时间日期信息
     * @param $value
     * @return bool|string
     */
    public function getCreateTimeAttr($value)
    {
        $status = date('Y-m-d', $value);
        return $status;
    }

    /**
     * 获取更新时间信息
     * @param $value
     * @return bool|string
     */
    public function getUpdateTimeAttr($value)
    {
        return ($value === 0)?'暂无':date('Y-m-d', $value);
    }

    /**
     * 设置状态信息
     * @param $val
     * @return int
     */
    public function setStatusAttr($val){
        if($val == 'on'){
            return 1;
        }
        return 0;
    }

    /**
     * 添加数据时设置时间
     * @param $val
     * @return int
     */
    public function setCreateTimeAttr($val){
        return time();
    }

    /**
     * 更新数据时设置时间
     * @param $val
     * @return int
     */
    public function setUpdateTimeAttr($val){
        return time();
    }
}