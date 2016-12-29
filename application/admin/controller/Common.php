<?php
namespace app\admin\controller;

use core\request;

class Common extends \think\Controller
{
    protected static $userData = null;
    //入口
    public function _initialize(){
        $req = Request();
        //拼接控制器和操作字符串
        $ctrlAct = $req->controller().'/'.$req->action();
        if(self::isLogin()){
            if(self::isPublicAct($ctrlAct)){
                $this->error(
                    '您已登录，无法访问！',
                    Url(config('default_index'))
                );
            }
        }else{
            if(!self::isPublicAct($ctrlAct)){
                $this->redirect(config('default_login'));
            }
        }
    }

    /**
     * 列表页
     * @return mixed|void
     */
    public function index(){
        if(method_exists($this, '_index')){
            $result = $this->_index($result);
        }
        $req = Request();
        if(Request()->isAjax()){
            $tableNmae = $req->controller();
            try{
                $model = model($tableNmae);
            }catch (\Exception $e){
                $model = db($tableNmae);
            }
            $map = $this->_CommonMap(Request()->param());
            if(method_exists($this, '_map')){
                $map = $this->_map($map);
            }
            $pageSize = $map['pageSize'];
            unset($map['pageSize']);
            unset($map['page']);
            $order = [
                'id'=> 'asc'
            ];
            $result = $model->pageData($map, $order, $pageSize);
            $action = Request()->action();
            if(method_exists($this, 'index_')){
                $result = $this->index_($result);
            }
            return $this->result($result, 1);
        }
        return $this->fetch('index');

    }

    /**
     *编辑
     * @return mixed
     */
    public function edit(){
        if(Request()->isAjax()){
            $data = $_POST;
            if(input('?param.id')){
                $result = $this->update($data, input('param.id'));
            }else{
                $result = $this->update($data);
            }
            return $this->result($result, 1);
            die;
        }
        if(method_exists($this, '_edit')){
            $this->_edit();
        }
        if(input('?param.id')){
            $result = model(Request()->controller())
                ->where('id','eq', input('param.id'))->find();
            if(method_exists($this, 'edit_')){
                $result = $this->edit_($result);
            }
            $this->assign('data', $result);
        }
        return $this->fetch();
    }

    /**
     * 删除数据
     */
    public function del(){
        if(Request()->isAjax()){
            if(!input('?post.id')){
                return $this->error('What!');
            }
            $id = input('post.id');
            $result = model(Request()->controller())
                ->destroy($id);
            return $this->result($result,1);
        }
        return $this->error('2333!');
    }

    /**
     * 更新&&添加数据
     * @param $id
     * @param $data
     * @return bool|false|int
     */
    private function update($data, $id = null){
        if(is_null($id)){
            $result = model(Request()->controller())
                ->save($data);
        }else{
            $result = model(Request()->controller())
                ->save($data, ['id' => $id]);
        }
        return $result;
    }

    /**
     * 条件处理
     * @param $map
     * @return mixed
     */
    public function _CommonMap($map){
        $keys = ['name','title', 'username'];
        foreach($map as $key => $val){
            if(empty($val)){
                unset($map[$key]);
            }else if(in_array($key, $keys)){
                $map[$key] = ['like','%'.$val.'%'];
            }
        }
        return $map;
    }

    /**
     * 检测权限
     * @param $url 控制器/操作
     * @return bool 是否有权限
     */
    private static function checkRole($url){
        if(self::$userData['super'] == 1){
            return true;
        }

        return false;
    }

    /**
     * 检测是不是公共方法
     * @param $url 控制器/操作
     * @return bool
     */
    private static function isPublicAct($url){
        return in_array($url, config('public_action'));
    }

    /**
     * 是否已经登录
     * @return bool
     */
    private static function isLogin(){
        return (session('?'.config('user_key')) && (self::$userData = session('?'.config('user_key'))));
    }

}