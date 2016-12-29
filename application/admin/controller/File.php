<?php
/**
 * Created by PhpStorm.
 * User: tingfeng-key.com
 * Date: 2016/11/2
 * Time: 11:16
 */

namespace app\admin\controller;


class File
{
    use \traits\controller\Jump;
    private static $dir = null;
    private static $upload_dir = null;
    public function __construct()
    {
        self::$dir = '/public/upload/';
        self::$upload_dir = ROOT_PATH.self::$dir;
    }

    /**
     * 文件下载
     */
    public function download(){

    }

    /**
     * 解压游戏模板
     * @param $data
     * @return mixed
     */
    private function zip($data){
        $file = ROOT_PATH.$data['saveFileName'];
        $zip = new \ZipArchive();
        $zip->open($file);
        $fileName = explode('.',$data['name']);
        $zip->extractTo('games/'.$fileName[0].'/');
        $zip->close();
        $data['saveFileName'] = '/games/'.$fileName[0].'/index.html';
        return $data;
    }
    private function image($data){
        $data['saveFileName'] = str_replace('/public','', $data['saveFileName']);
        return $data;
    }
    /**
     * 文件上传
     * @return \think\response\Json|void
     */
    public function upload(){
        $type = config('file_obj');
        $result = $this->fileDeal($type);
        if($result['code'] == 0){
            return json($result);
        }else{
            $check = $this->dbCheck(self::$upload_dir.$result['data']['saveFileName']);
            if(false === $check){
                $this->saveFileTodb($result['data']);
                $result['data']['saveFileName'] = self::$dir.$result['data']['saveFileName'];
            }else{
                $result['data']['saveFileName'] = $check;
            }
            if(input('?param.deal')){
                $param = input('param.deal');
                $result['data'] = $this->$param($result['data']);
            }
            unset($result['data']['tmp_name']);
            return json($result);
        }
    }

    /**
     * 文件上传处理
     * @param $type
     * @return array
     */
    private function fileDeal($type){
        $file = request()->file($type);
        $result = [
            'code' => 0,
            'data' => [],
            'msg' => '',
        ];
        if(is_null($file)){
            $result['msg'] = '上传错误：上传参数不正确！';
            return $result;
        }
        $info = $file->move(self::$upload_dir);
        if($info){
            $data = $info->getInfo();
            $data['saveFileName'] = $info->getSaveName();
            $result = [
                'code' => 1,
                'data' => $data,
                'msg' => 'success',
            ];
        }else{
            $result['msg'] = $file->getError();
        }
        return $result;
    }

    /**
     * 数据库文件验证是否存在
     * @param $file
     * @return bool
     */
    private function dbCheck($file){
        $map['file_md5'] = md5_file($file);
        $result = db('Attachment')->where($map)->find();
        if(is_null($result)){
            return false;
        }
        return self::$dir.$result['save_name'];
    }

    /**
     * 文件信息保存
     * @param $fileInfo
     * @return int|string
     */
    private function saveFileTodb($fileInfo){
        $data = [
            'file_name' => $fileInfo['name'],
            'save_name' => $fileInfo['saveFileName'],
            'file_dir' => self::$dir,
            'file_md5' => md5_file(self::$upload_dir.$fileInfo['saveFileName']),
            'type' => $fileInfo['type'],
            'size' => $fileInfo['size'],
            'create_time' => time()
        ];
        $result = db('Attachment')->insert($data);
        return $result;
    }
}