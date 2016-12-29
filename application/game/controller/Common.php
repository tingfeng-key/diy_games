<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/10/24
 * Time: 11:48
 */

namespace app\game\controller;

use think\Exception;

class Common extends \think\Controller
{
    /**
     * 异步get请求
     * @param $url
     * @return string
     * @throws Exception
     */
    public function http_get($url){
        if(function_exists('file_get_contents')){
            $contents = file_get_contents($url);
        }else if(function_exists('curl_init')){

        }else{
            throw new Exception('您的主机不支持curl和file_get_contents函数，请先开启！');
        }
        return $contents;
    }

    /**
     * 异步post请求
     * @param $url
     * @param $data
     * @return mixed
     */
    public function http_post($url, $data){
        /*初始化*/
        $ch=curl_init();

        /*设置变量 */
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        /*post的变量*/
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        /*执行并获取结果*/
        $result = curl_exec($ch);

        /*释放cURL句柄*/
        curl_close($ch);
        return $result;
    }

    /**检测是否从平台登录
     * @return bool|mixed
     */
    protected function isLogined(){
        if(!isset($_SESSION['t'])){
            session_start();
            $_SESSION['t'] = 'diy';
        }
        if(!isset($_COOKIE['s_token'])){
            return false;
        }
        $database = config('database');
        $database['database'] = 'jiuhu_db';
        $database['prefix'] = 'fz_';
        $dbObj = db('statistics_user', $database, false);
        $data = $dbObj->where([
            'token' => $_COOKIE['s_token'],
            //'ip' => $info['ip_address']
        ])->find();
        if(is_null($data)){
            return false;
        }
        return $data['id'];
    }
}