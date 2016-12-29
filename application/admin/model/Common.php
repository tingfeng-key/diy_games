<?php
namespace app\admin\model;

use think\Model;

class Common extends Model
{

    public function pageData($map, $order, $pageSize = 10){
        $result = $this->where($map)->order($order)->paginate($pageSize);
        if(method_exists($this,'_field')){
            $result = $this->_field($result);
        }
        return $result;
    }
}