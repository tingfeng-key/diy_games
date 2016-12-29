<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/10/30
 * Time: 20:53
 */

namespace app\admin\controller;


class SysRole extends Common
{

    public function _edit(){
        $tree = new Tree;
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $data = $this->getOptions();
        $tree->init($data);
        $str = "<option value=\$id \$select>\$spacer\$name</option>";
        $ids = model('SysRole')->getNodeIds(input('param.id'));
        $ids = empty($ids)?[]:explode(',',$ids);
        $this->assign('options', $tree->get_tree(0,$str, $ids));
    }
    private function getOptions(){
        $nodeIds = model('SysRole')->getNodeIds(1);
        $data = model('SysNode')->getNodeData($nodeIds, 'id,pid,name');
        $arr = [];
        foreach($data as $key => $val){
            $arr[$key] = [
                'id' => $val['id'],
                'name' => $val['name'],
                'pid' => $val['pid']
            ];
        }
        return $arr;
    }
}