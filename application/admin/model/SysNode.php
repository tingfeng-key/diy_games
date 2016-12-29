<?php
namespace app\admin\model;

use think\Model;

class SysNode extends Model
{
    /**
     * 获取左侧菜单数据
     * @return array
     */
    public function getMenu(){
        $userData = session(config('user_key'));
        $nodeIds = model('SysRole')->getNodeIds($userData->role_id);
        $data = $this->getNodeData($nodeIds, 'id,pid,name,c,a,icon');
        return $this->childNode($data);
    }

    /**
     * 递归处理多级节点
     * @param $data
     * @param int $pid
     * @return array
     */
    public function childNode($data, $pid = 0){
        $vals = [];
        foreach($data as $key => $val){
            if($val->pid == $pid){
                $childData = $this->childNode($data, $val->id);
                $childData && $val['child'] = $childData;
                $val['url'] = Url($val['c'].'/'.$val['a']);
                $vals[] = $val;
            }
        }
        return $vals;
    }

    /**
     * 获取节点数据
     * @param $ids
     * @param string $field
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNodeData($ids, $field = '*'){
        if($ids == -1){
            $nodeData = $this->field($field)->select();
        }else{
            $nodeData = $this->where('id', 'in', $ids)->field($field)->order([
                'sort',
                'id'
            ])->select();
        }
        return $nodeData;
    }
}