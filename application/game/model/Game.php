<?php
/**
 * 游戏管理
 * Created by IntelliJ IDEA.
 * User: tingfeng
 * Date: 2016/12/23
 * Time: 11:02
 */

namespace app\game\model;


class Game extends Common
{
    private $_dbMap;
    public $_map;
    public $_pageSize = 10;
    public $_field = null;

    public function initialize()
    {
        parent::initialize();
        $this->_dbMap = db("Game");
    }

    /**
     * 获取游戏列表
     * @return \think\paginator\Collection
     */
    public function getList(){
        $this->joinType()
            ->searchName()
            ->searchType()
            ->setField();
        $result = $this->_dbMap->paginate($this->_pageSize);
        return $result;
    }

    /**
     * 字段处理
     * @return $this
     */
    private function setField() {
        if(!is_null($this->_field)){
            $this->_dbMap->field($this->_field);
        }
        return $this;
    }

    /**
     * 类型检索
     * @return $this
     */
    private function joinType(){
        try{
            $this->_dbMap->alias('g')
                ->join('__TYPE__ t', 'g.type_id = t.id');
        }catch (\Exception $e){
            trace($e->getMessage(), 'error');
        }
        return $this;
    }

    /**
     * 名称检索
     * @return $this
     */
    private function searchName(){
        if(!empty($this->_map['name'])){
            $this->_dbMap->where(
                'g.name',
                'like',
                '%'.$this->_map['name'].'%'
            );
        }
        return $this;
    }

    /**
     * 类型检索
     * @return $this
     */
    private function searchType(){
        if(!empty($this->_map['type'])){
            $this->_dbMap->where(
                'g.type_id',
                'eq',
                $this->_map['type']
            );
        }
        return $this;
    }
}