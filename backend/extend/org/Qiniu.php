<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 2018/5/10
 * Time: 13:47
 */
namespace org;
use think\Controller;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use Qiniu\Config;
use Qiniu\Processing\PersistentFop;
use Qiniu\Storage\ResumeUploader;

class Qiniu extends Controller
{
    //引入文件
    public function _initialize(){
        require_once '../extend/qiniu/autoload.php';
    }

    /**
     * auth 鉴权
     * @return Auth
     */
    public function auth(){
        // 用于签名的公钥和私钥
        $accessKey = config('qiniu.accessKey');
        $secretKey = config('qiniu.secretKey');
        // 初始化签权对象
        $auth = new Auth($accessKey, $secretKey);
        return $auth;
    }

    /**
     * uploadFile 上传文件(图片&音频)
     * @param $filePath 文件地址
     * @param $key 组装的文件名
     * @param $domain 前缀
     * @return string 返回上传地址
     * @throws \Exception
     */
    public function uploadFile($filePath,$key){
        $auth = $this->auth();
        //储存空间
        $bucket = config('qiniu.bucket');

        $policy = array(
            'callbackBody' => 'filename=$(fname)&filesize=$(fsize)'
        );
        // 生成上传Token
        $uptoken = $auth->uploadToken($bucket, null, 3600, $policy);
        // 构建 UploadManager 对象
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($uptoken, $key, $filePath);
        if ($err !== null) {
            return $ret['key'];
        } else {
            return $ret['key'];
        }
    }

    /**
     * 上传视频&转码
     * @param $filePath 文件地址
     * @param $key 组装的文件名
     * @param $domain 前缀
     * @return string 返回上传地址
     * @throws \Exception
     */
    public function uploadVideo($filePath,$key){
        $auth = $this->auth();
        //储存空间
        $bucket = config('qiniu.bucket');

        $policy = array(
            'callbackBody' => 'filename=$(fname)&filesize=$(fsize)'
        );
        // 生成上传Token
        $uptoken = $auth->uploadToken($bucket, null, 3600, $policy);
        // 构建 UploadManager 对象
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($uptoken, $key, $filePath);
//        if ($err !== null) {
//            return $ret['key'];
//        } else {
//            return $ret['key'];
//        }
        $config = new Config();
        $pipeline = config('pipeline');
        $force = false;
        $k =  $ret['key'];
        $pfop = new PersistentFop($auth, $config);
        $name = md5(time().uuid()).".mp4";
        $fops = "avthumb/mp4/vcodec/libx264|saveas/" . \Qiniu\base64_urlSafeEncode($bucket .':'. $name);
        list($id, $err) = $pfop->execute($bucket, $k, $fops, $pipeline, '', $force);
        //查询转码的进度和状态
        list($sta, $err) = $pfop->status($id);
//        echo "\n====> pfop avthumb status: \n";
        if ($sta != null) {
            return $name;
        } else {
            return $name;
        }
    }

    /**
     * deleteFile 删除图片
     * @param $key 图片名称
     * @param $bucket
     * @return mixed
     */
    public function delFile($key,$bucket){
        $auth = $this->auth();
        $config = new Config();
        $bucketManager = new BucketManager($auth, $config);
        $err = $bucketManager->delete($bucket, $key);
        return $err;
    }

    /**
     * delMoreFile 批量删除
     * @param $keys 图片数组 //每次最多不能超过1000个
     * @param $bucket 空间名
     * @return mixed
     */
    public function delMoreFile($keys,$bucket){
        $auth = $this->auth();
        $config = new Config();
        $bucketManager = new BucketManager($auth, $config);
        $ops = $bucketManager->buildBatchDelete($bucket, $keys);
        list($ret, $err) = $bucketManager->batch($ops);
        if ($err) {
            return $err;
        } else {
            return $ret;
        }
    }
}