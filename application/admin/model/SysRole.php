<?php
namespace app\admin\model;

use think\Model;

class SysRole extends Common
{

    public function getStatusAttr($val){
        $stauts = [
            '0' => '禁用',
            '1' => '启用'
        ];
        return $stauts[$val];
    }
    /**
     * 获取节点id字符串
     * @param $id
     * @return mixed
     */
    public function getNodeIds($id){
        return $this->where('id', 'eq', $id)->value('node_ids');

    }
}