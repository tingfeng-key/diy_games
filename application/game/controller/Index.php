<?php
/**
 * Created by IntelliJ IDEA.
 * User: tingfeng
 * Date: 2016/12/23
 * Time: 10:55
 */

namespace app\game\controller;

use think\Request;

class Index extends Common
{
    private static $member_id = 0;
    public function _initialize()
    {
        parent::_initialize();
        $action = Request()->action();
        $publicAction = ['start','pay_callback','index','getJsapiTicket'];
        $isStartUrl = (in_array($action, $publicAction))?false:true;
        if(!$this->isLogined() && $isStartUrl){
            //$this->redirect(config('login_url'));
        }
        self::$member_id = $this->isLogined();
    }
    public function index(){
        $map = Request()->param();
        $gameModel = model('Game');
        $gameModel->_map = $map;
        $gameModel->_field = [
            'g.*',
            't.name' => 'type_name'
        ];
        $data = $gameModel->getList();
        $gtModel = model('GameType');
        $gtModel->_sort = [
            'sort' => 'asc'
        ];
        $typeData = $gtModel->getList();
        $this->assign([
            'data'=>$data,
            'typeData' => $typeData
        ]);
        return (Request()->isAjax())?$this->result($data):$this->fetch();
    }

    public function buy(){
        $id = input('param.id');
        $params = date('YmdHis').rand(1111,9999);
        $gameObj = model('game')->where('id',$id)->find();
        if(is_null($gameObj)){
            $this->error('游戏不存在哦~', 'game/Index/index');
        }
        $gamePrice = $gameObj['price'];
        $gameName = $gameObj['name'];
        $gameLogo = $gameObj['logo'];
        $secret = 'fz_diy_games';
        $data = [
            'appkey' => 'diy_games_key',//游戏标识
            'params' => $params,//订单号
            'uid' => 2937,//用户Id
            'pid' => 108,//角色ID
            'gold' => $gamePrice,//金钱数量
            'time' => time(),//时间戳
            't' => 'diy',//接口标识
        ];
        //签名
        $data['sign'] = md5($params.$data['pid'].$data['uid'].
            $data['gold'].$data['appkey'].$secret.$data['time']);
        $url = 'http://www.fz222.com/api/pay?'.http_build_query($data);
        $map = [
            'game_id' => $id,
            'member_id' => self::$member_id,
            'order_sn' => $data['params'],
        ];
        $dbObj = db('member');
        $result = $dbObj->insert($map);
        if(!$result){
            $lastSql = $dbObj->getLastSql();
            trace('用户购买游戏时出错，SQL：'.$lastSql);
            return $this->error('系统异常', Url('game/Member/mygame'));
        }
        //$this->redirect($url);
    }

    /**
     * 开始游戏
     * @return mixed
     */
    public function start(){
        if(!input('?param.id')){
            die;
        }
        $id = input('param.id');
        $this->hitSetInc($id);
        $data = model('Member')->findGame($id);
        if(is_null($data)){
            trace("游戏不存在", "error");
            die;
        }
        $this->redirect(ROUTE_ROOT.'games/'. $data['tpl_path']. '?id='.$id);
        //return $this->fetch('start', ['data' => $data]);
    }

    /*
     * div游戏
     */
    public function make(){
        $id = input('param.id');
        $map = [
            'id' => $id,
            'member_id' => self::$member_id,
        ];
        $gmModel = model('Member');
        $gmData = $gmModel->where($map)->find();
        if(is_null($gmData)) return ;
        if(Request()->isAjax()){
            if($gmData['game_attach_id'] == 0){
                $result = $this->addData($gmData['game_id']);
                $gmModel->where($map)->setField('game_attach_id', $result);
            }else{
                $result = $this->editData($gmData['game_id'], $gmData['game_attach_id']);
            }
            if(!$result && !$gmData['game_attach_id']){
                return $this->result([], 0, "失败");
            }else if($result || $gmData['game_attach_id']){
                $url = Request()->domain().Url('game/Index/start', ['id' => $id]);
                $faceUrl1 = '<img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f';
                $faceUrl2 = '.png" />';
                $message = '<h1>由于微信的霸权主义'.$faceUrl1.'25'.$faceUrl2;
                $message .= '，导致游戏分享微信朋友圈时，可能会被强制封杀'.$faceUrl1.'09';
                $message .= $faceUrl2.'，建议使用其他分享方式'.$faceUrl1.'23'.$faceUrl2.'！！！</h1>';
                return $this->result($url, 1, $message);
            }
        }
        $gModel = model('game');
        $gData = $gModel->find($gmData['game_id']);
        if(is_null($gData)) return ;
        $aModel = db("attach_". $gData["db_table"]);
        $gAttach = $aModel->find($gmData['game_attach_id']);
        $param = config('jsonConfig');
        $param['_save_logo'] = Url('saveLogo');
        $param['data'] = null;
        if(!is_null($gAttach)){
            $param['data'] = $gAttach;
        }
        return $this->fetch('makes:' .$gData['db_table'], [
            'data' => json_encode($param)
        ]);
    }

    /**
     * 添加数据
     * @param $id
     * @return int|string|void
     */
    private function addData($id){
        $gModel = model('game');
        $gData = $gModel->find($id);
        if(is_null($gData)){
            return $this->result("游戏已被删除");
        }
        $aModel = db("attach_". $gData["db_table"]);
        $result = $aModel->insert($_POST);
        if(!$result) return 0;
        return $aModel->getLastInsID();
    }

    /**
     * 编辑数据
     * @param $id
     * @param $attachId
     * @return int|string|void
     */
    private function editData($id, $attachId){
        $gModel = model('game');
        $gData = $gModel->find($id);
        if(is_null($gData)){
            return $this->result("游戏已被删除");
        }
        $aModel = db("attach_". $gData["db_table"]);
        $result = $aModel->where('id', $attachId)->update($_POST);
        return $result;
    }

    /**
     * 获取游戏配置
     */
    public function game_get_config(){
        $id = input('param.id');
        $gmData = model('Member')->find($id);
        if(is_null($gmData)) return ;
        if(!$gmData['game_attach_id']) return $this->game_default_config($gmData['game_id']);
        $gModel = model('Game');
        $gData = $gModel->find($gmData['game_id']);
        $aModel = db('attach_'.$gData['db_table']);
        $aData = $aModel->find($gmData['game_attach_id']);
        if(is_null($aData)) return ;
        return $this->result($aData, 1, "获取数据成功",'json');
    }

    /**
     * 获取默认配置
     * @param $id
     */
    private function game_default_config($id){
        $gModel = model('Game');
        $gData = $gModel->find($id);
        $aModel = db('attach_'.$gData['db_table']);
        $aData = $aModel->find($gData['default']);
        return $this->result(is_null($aData)?[]:$aData, 1, "获取默认配置");
    }

    /**
     * 获取jsapiTicket
     */
    public function getJsapiTicket(){
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.
            $this->getAccessToken().'&type=jsapi';
        $jsapiTickt = json_decode($this->http_get($url), true);
        $data = $jsapiTickt;
        $data['noncestr'] = rand(1,9999);
        $data['timestamp'] = time();
        $data['signature'] = sha1("jsapi_ticket=".$data['ticket']."&noncestr=".$data['noncestr'].
            "&timestamp=".$data['timestamp']."&url=".input('post.data'));
        $this->success('成功','',$data);
    }

    /**
     * 获取accessToken
     * @return mixed
     */
    private function getAccessToken(){
        $appId = 'wxd0aa86719fd88894';
        $appser = '68e19b998d39e53d99523b36c0996314';
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.
            $appId.'&secret='.$appser;
        $accessToken = json_decode($this->http_get($url), true);
        return $accessToken['access_token'];
    }

    /**
     * 点击更新数据
     * @param $id
     * @throws \think\Exception
     */
    private function hitSetInc($id){
        $field = userAgent().'_number';
        $model = model('Member');
        $model->where('id', 'eq', $id)->setInc('hit_number');
        $model->where('id', 'eq', $id)->setInc($field);
        $joinStr = 'gm.game_id = g.id && gm.id = '.$id;
        $data = $model->alias('gm')
            ->join('__GAME__ g', $joinStr)->find();
        if(!is_null($data)){
            db('Game')->where('id', 'eq', $data['game_id'])->setInc('hit_number');
        }
    }

    /**
     * 保存Logo
     */
    public function saveLogo(){
        if(!input('?post.imageData')){
            return $this->error('参数错误');
        }
        $imageData = input('post.imageData');
        $uploadDir = 'upload/'.date('Ymd', time());
        $dir = ROOT_PATH.'public/'.$uploadDir;
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $fileName = md5(time().rand(0,9999));
        $data = explode(',',$imageData);
        if(file_put_contents($dir.'/'.$fileName.'.png', base64_decode($data[1]))){
            $image = \think\Image::open($dir.'/'.$fileName.'.png');
            $image->thumb(300,300,\think\Image::THUMB_CENTER)->save($dir.'/thumb_'.$fileName.'.png');
            $url = $uploadDir.'/thumb_'.$fileName.'.png';
            return $this->result('logo保存成功', 1,$url);
        }else{
            return $this->result('logo保存失败', 0);
        }
    }

    /**
     * 异步处理大图片
     */
    public function baseImage(){
        if(!input('?post.imageData')){
            return $this->error('参数错误');
        }
        $imageData = input('post.imageData');
        $tmpDir = 'upload/tmp/'.date('Ymd', time());
        $dir = ROOT_PATH.'public/'.$tmpDir;
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $fileName = md5(time().rand(0,9999));
        $data = explode(',',$imageData);
        if(file_put_contents($dir.'/'.$fileName.'.png', base64_decode($data[1])) !== false){
            $image = \think\Image::open($dir.'/'.$fileName.'.png');
            $image->thumb(300,300,\think\Image::THUMB_CENTER)->save($dir.'/thumb_'.$fileName.'.png');
            $url = ROUTE_ROOT.$tmpDir.'/thumb_'.$fileName.'.png';
            return $this->success('保存成功',$url);
        }else{
            return $this->error('保存失败');
        }
    }

    /**
     * 支付回调
     */
    public function pay_callback(){
        //die;
        if(!input('?param.out_trade_no')){
            return $this->error('参数错误');
        }
        $orderSn = input('param.out_trade_no');
        $dbObj = db('game_member');
        $orderMap = [
            'order_sn' => $orderSn,
            'order_status' => 0,
        ];
        $isExists = $dbObj->where($orderMap)->value('id');
        if(is_null($isExists)){
            return $this->error('订单不存在', 'game/Index/index');
        }
        $result = $dbObj->where($orderMap)->update([
            'order_status' => 1,
            'order_pay_time' => time()
        ]);
        if(!$result){
            $lastSql = $dbObj->getLastSql();
            trace('用户支付时，修改用户订单失败，Sql：'.$lastSql, 'info');
            return $this->error('系统异常', Url('game/Index/index'));
        }
        return $this->success('支付成功', Url('game/Index/make', ['id' => $isExists]));
    }
}