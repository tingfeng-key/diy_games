<?php
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
            $this->redirect(config('login_url'));
        }
        self::$member_id = $this->isLogined();
    }

    /*
     * 游戏列表
     */
    public function index()
    {
        $map = Request()->param();
        $data = model('Game')->getAll($map);
        $typeData = db('Type')->order(['sort' => 'asc'])->select();
        $this->assign([
            'data'=>$data,
            'typeData' => $typeData
        ]);
        return (Request()->isAjax())?$this->result($data):$this->fetch();
    }

    /**
     * 购买页面
     */
    public function buy(){
        $id = input('param.id');
        $params = date('YmdHis').rand(1111,9999);
        $gameObj = model('game')->where('id',$id)->find();
        if(is_null($gameObj)){
            $this->error('游戏不存在哦~', 'game/Index/index');
        }
        //dump($gameObj);die;
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
            'name' => $gameName,
            'font' => '"微软雅黑"',
            'grade' => '5',
            'color' => '1',
            'reward' => 'a:1:{i:0;a:2:{s:5:"score";s:0:"";s:3:"url";s:0:"";}}',
            'share' => '2048！让你感受数字的魅力！！！',
            'create_time' => time(),
        ];
        $dbObj = db('game_member');
        $result = $dbObj->insert($map);
        if(!$result){
            $lastSql = $dbObj->getLastSql();
            trace('用户购买游戏时出错，SQL：'.$lastSql);
            return $this->error('系统异常', Url('game/Member/mygame'));
        }
        $this->redirect($url);

    }
    /*
     * div游戏
     */
    public function make(){
        $id = input('param.id');
        $map = [
            'id'=>$id,
            'member_id' => self::$member_id,
        ];
        $gameMember = model('GameMember');
        $data = $gameMember->where($map)->find();
        if(Request()->isAjax()){
            $postData = $_POST;
            /*$rule = [
                'name' => 'require|min:1|max:10',
                'logo' => 'require|min:5',
                'font' => 'require|min:2',
                'color' => 'number|min:1|max:8',
                'reward' => 'require',
                'reward_url' => 'require',
                'share' => 'max:200',
            ];
            $msg = [
                'name' => '名称不能为空且最大不能超过10个字符',
                'logo' => 'Logo不能为空',
                'font' => '字体不能为空',
                'color' => '颜色卡不能为空',
                'reward' => '达到奖励不能为空',
                'reward_url' => '奖励链接不正确',
                'share' => '分享的文字最多不能超过200个',
            ];
            $ruleResult = $this->validate($postData,$rule, $msg);
            if(true !== $ruleResult){
                return $this->error($ruleResult);
            }*/
            $postData['reward'] = $this->reward($postData);
            unset($postData['reward_url']);
            if(empty($data)){
                $postData['member_id'] = self::$member_id;
                $postData['id'] = $id;
                $result = $gameMember->data($postData)->save();
            }else{
                $postData['update_time'] = time();
                $result = $gameMember->save($postData,$map);
            }
            if($result !== false){
                $lastId = $gameMember->where($map)->value('id');
                $url = Request()->domain().Url('game/Index/start', ['id'=>$lastId]);
                $faceUrl1 = '<img src="http://tb2.bdstatic.com/tb/editor/images/face/i_f';
                $faceUrl2 = '.png" />';
                $message = '<h1>由于微信的霸权主义'.$faceUrl1.'25'.$faceUrl2.'，导致游戏分享微信朋友圈时，可能会被强制封杀'.$faceUrl1.'09'.$faceUrl2.'，建议使用其他分享方式'.$faceUrl1.'23'.$faceUrl2.'！！！</h1>';
                return $this->result($url,1, $message);
            }else{
                return $this->result(0, 0, '制作失败');
            }

        }
        $fonts = [
            [
                'name' => '微软雅黑',
                'val' => '"微软雅黑"'
            ],
            [
                'name' => 'voltergoldfish',
                'val' => '"voltergoldfish"',
            ],
            [
                'name' => 'AgencyFB',
                'val' => 'AgencyFB'
            ],
            [
                'name' => 'kristenitc',
                'val' => 'kristenitc'
            ],
        ];
        $grades = [
            [
                'name' => '放水',
                'val' => 6,
            ],
            [
                'name' => '轻松',
                'val' => 5,
            ],
            [
                'name' => '正常',
                'val' => 4,
            ],
            [
                'name' => '噩梦',
                'val' => 3,
            ],
        ];
        $colors = [
            [
                'name' => '经典',
                'val' => 1,
                'bgc' => '#f5caa5',
            ],
            [
                'name' => '简约',
                'val' => 2,
                'bgc' => '#e6fabe',
            ],
            [
                'name' => '清新',
                'val' => 3,
                'bgc' => '#ccffff',
            ],
            [
                'name' => '复古',
                'val' => 4,
                'bgc' => '#ffffbf',
            ],
        ];
        return $this->fetch('make', [
            'data' => $data,
            'fonts' => $fonts,
            'grades' => $grades,
            'colors' => $colors
        ]);
    }

    private function reward($data){
        $arr = [];
        foreach($data['reward'] as $key => $val){
            $arr[$key] = [
                'score' => $data['reward'][$key],
                'url' => $data['reward_url'][$key],
            ];
        }
        return serialize($arr);
    }

    /*
     * 开始游戏
     */
    public function start(){
        if(!input('?param.id')){
            die;
        }
        $id = input('param.id');
        $field = [
            'gm.*',
            'g.logo' => 'g_logo',
            'g.tpl' => 'tpl',
        ];
        //$this->getJsapiTicket();
        $this->hitSetInc($id);
        $data = model('GameMember')->alias('gm')
            ->join('__GAME__ g', 'gm.game_id = g.id && gm.id = '.$id)
            ->field($field)->find();
        return $this->fetch('start', ['data' => $data]);
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
        $model = model('GameMember');
        $model->where('id', 'eq', $id)->setInc('hit_number');
        $model->where('id', 'eq', $id)->setInc($field);
        $joinStr = 'gm.game_id = g.id && gm.id = '.$id;//.' && g.default_member_id = '.self::$member_id;
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
