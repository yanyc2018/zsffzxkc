<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use think\Controller;
use think\File;
use think\Request;
use think\Seeeion;
use think\Db;
use org\Qiniu;
use org\Upayun;
class Upload extends Base
{
    /**
     * 上传图片到又拍云
     * @throws \Exception
     */
    public function uploadOnYpy(){
        $filePath = $_FILES['file']['tmp_name'];
        //取出图片后缀
        $type = explode(".",$_FILES['file']['name']);
        $type = end($type);
        //组装图片名
        $key = md5(time().uuid()).'.'.$type;
        $up = new Upayun();
        $data = $up->uploadFile($filePath,$key,'/up/');
        echo $data;
    }

    /**
     * 删除又拍云图片文件
     * @return \think\response\
     */
    public function deleteYpy(){
        $add = input('add');
        $up = new Upayun();
        $res = $up->delFile($add);
        if($res){
            return json(['code'=>200,'msg'=>'删除成功！']);
        }else{
            return json(['code'=>100,'msg'=>'删除失败！']);
        }
    }

    /**
     * deleteImg 删除七牛图片文件
     * @return \think\response\
     */
    public function deleteImg(){
        $add = input('add');
        $up = new Qiniu();
        $res = $up->delFile($add,'kevin');
        if($res){
            return json(['code'=>100,'msg'=>'删除失败！']);
        }else{
            return json(['code'=>200,'msg'=>'删除成功！']);
        }
    }


    /**
     * upload 上传图片到七牛云
     * @throws \Exception
     */
    public function upload(){
        $filePath = $_FILES['file']['tmp_name'];
        //取出图片后缀
        $type = explode(".",$_FILES['file']['name']);
        $type = end($type);
        //组装图片名
        $key = md5(time().uuid()).'.'.$type;
        $up = new Qiniu();
        $data = $up->uploadFile($filePath,$key);
        echo $data;
    }

    /*
     * layui上传图片&音频
     */
    public function layUpload(){
        set_time_limit (0);
        $filePath = $_FILES['file']['tmp_name'];
        //取出图片后缀
        $type = explode(".",$_FILES['file']['name']);
        $type = end($type);
        //组装图片名
        $key = md5(time().uuid()).'.'.$type;
        $up = new Qiniu();
        $data = $up->uploadFile($filePath,$key);
        return json(['code'=>0,'msg'=>'','data'=>['src'=>config('qiniu.domain').$data]]);
    }

    /*
     * 上传视频
     */
    public function layUploadVideo(){
        set_time_limit (0);
        $filePath = $_FILES['file']['tmp_name'];
        //取出文件后缀
        $type = explode(".",$_FILES['file']['name']);
        $type = end($type);
        //组装文件名
        $key = md5(time().uuid()).'.'.$type;
        $up = new Qiniu();
        $data = $up->uploadVideo($filePath,$key);
        echo $data;
    }


    /*
     * wangEditor图片上传
     */
    public function wangUpload(){
        foreach($_FILES as $key=>$vo){
            $filePath = $vo['tmp_name'];
            //取出图片后缀
            $type = explode(".",$vo['name']);
            $type = end($type);
            //组装图片名
            $key = md5(time().uuid()).'.'.$type;
            $up = new Qiniu();
            $name = $up->uploadFile($filePath,$key);
            $data[] = config('qiniu.domain').$name;
        }
        return json(['errno'=>0,'data'=>$data]);
    }


    /**
     * 百度富文本上传图片至第三方CDN接口
     * @throws \Exception
     */
    public function ueditorUpload(){
        $file = request()->file('upfile');
        $info  = $file->getInfo();
        //取出图片后缀
        $type = explode(".",$info['name']);
        $type = end($type);
        //组装图片名
        $key = md5(time().uuid()).'.'.$type;
        $up = new Qiniu();
        $data = $up->uploadFile($info['tmp_name'],$key);
        //百度富文本上传文件到CDN upFile
        $res = array(
            "state"    => "SUCCESS",          //上传状态，上传成功时必须返回"SUCCESS"
            "url"      => config('qiniu.domain').$data,            //CDN地址
            "title"    => $key,          //新文件名
            "original" => $info['tmp_name'],       //原始文件名
            "type"     => ".".$type,           //文件类型
            "size"     => $info['size'],           //文件大小
        );
        echo json_encode($res);
    }

    /**
     * webupload Locality图片上传至本地&压缩
     */
    public function uploadLocality(){
        $file = request()->file('file');
        $dir = ROOT_PATH . 'public' . DS . 'uploads/images/';
        if(!file_exists($dir)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($dir, 0700,true);
        }
        $info = $file->move($dir);
        if($info){
            $newName = $info->getSaveName();
            //压缩图片
//            image_png_size_add(ROOT_PATH . 'public' . DS . 'uploads/images/'.$newName,ROOT_PATH . 'public' . DS . 'uploads/images/'.$newName);
            $filepath="/uploads/images/".$newName;
            return $filepath;
        }else{
            $this->result(1,$file->getError());
        }
    }
    /**
     * layui图片上传至本地&压缩
     */
    public function uploadLocal(){
        $file = request()->file('file');
        $dir = ROOT_PATH . 'public' . DS . 'uploads/images/';
        if(!file_exists($dir)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($dir, 0700,true);
        }
        $info = $file->move($dir);
        if($info){
            $newName = $info->getSaveName();
            //压缩图片
//            image_png_size_add(ROOT_PATH . 'public' . DS . 'uploads/images/'.$newName,ROOT_PATH . 'public' . DS . 'uploads/images/'.$newName);
            $filepath="/uploads/images/".$newName;
            $this->result(0,'上传成功',$filepath,0,'json');
        }else{
            $this->result(1,$file->getError());
        }
    }
    /**
     * deleteLocality 删除本地图片
     * @return \think\response\Json
     */
    public function deleteLocality(){
        $add  = input('add');
        $add = substr ($add,1);
        if(unlink($add)){
            return json(['code'=>200,'msg'=>'删除成功！']);
        }else{
            return json(['code'=>100,'msg'=>'删除失败！']);
        }
    }


    /**
     * video 视频文件上传至本地
     */
    public function video(){
        @set_time_limit(5 * 60);
        $targetDir = ROOT_PATH . 'public' . DS . 'uploads/video_tmp';
        $uploadDir = ROOT_PATH . 'public' . DS . 'uploads/video/'.date('Ymd');
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
// Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir,0700,true);
        }

// Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir,0700,true);
        }

// Get a file name
        if (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }

        $filePath = $targetDir . DS . iconv("UTF-8","gb2312",$fileName);
//        $uploadPath = $uploadDir . DS . iconv("UTF-8","gb2312",$fileName);

// Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

// Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DS . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


// Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }
        if ( $done ) {
            $name = uuid();
//            if (!file_exists($uploadDir . DS . $name)) {
                @mkdir($uploadDir . DS . $name,0700,true);
//            }
            $uploadPath = $uploadDir . DS . $name . DS . iconv("UTF-8","gb2312",$fileName);
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
            echo 'uploads/video/'.date('Ymd'). '/' .$name. '/' .$fileName;
        }
    }



    //后台用户修改头像上传
    public function updateFace(){
        $base64url = input('base64url');
        $arr = base64_img($base64url,true);
        if($arr['code'] == 200){
            $res = Db::name('admin')->where('id',input('id'))->update(["portrait"=>$arr['msg']]);
            if($res){
                session('portrait', $arr['msg']); //用户头像
                return json(['code'=>200,'msg'=>"上传成功"]);
            }else{
                return json(['code'=>100,'msg'=>"上传失败"]);
            }
        }elseif($arr['code'] == 100){
            writelog('管理员上传头像失败',100);
            return json($arr);
        }
    }

    //多图修改测试页面
    public function showImg(){
        $photo = Db::name('img')->where('id',1)->value('img');
        $arr = explode(',',$photo);
        $this->assign ('photo',$photo);
        $this->assign ('arr',$arr);
        return $this->fetch('/webuploader2');
    }
}