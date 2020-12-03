<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 2018/7/10
 * Time: 12:55
 */
namespace org;
use think\Controller;
use Upyun\Upyun;
use Upyun\Config;

class Upayun extends Controller
{
    //引入文件
    public function _initialize(){
        require_once '../extend/upyun/vendor/autoload.php';
    }

    //初始化创建实例
    public function client(){
        // 创建实例
        $bucketConfig = new Config(config('upyun.serviceName'), config('upyun.operatorName'), config('upyun.operatorPassword'));
        $client = new Upyun($bucketConfig);
        return $client;
    }

    /**
     * 上传图片
     * @param $filePath 本地图片地址
     * @param $key 自定义图片名
     * @param $dir 目录
     * @return string
     * @throws \Exception
     */
    public function uploadFile($filePath,$key,$dir){
        $client = $this->client();
        // 创建目录
        if($dir != "" && !file_exists($dir)){
            $client->createDir($dir);
        }
        // 读文件
        $file = fopen($filePath, 'r');
        // 上传文件
        $res = $client->write($dir.$key, $file);
        return $dir.$key;
    }

    /**
     * 删除又拍云图片文件
     * @param $key 图片完整地址
     * @param $http 域名
     * @return bool
     * @throws \Exception
     */
    public function delFile($key){
        $add = str_replace (config('upyun.domain'),'',$key);
        $add = str_replace ('http://','',$add);
        $client = $this->client();
        // 单文件删除
        $res=$client->delete($add);
        return $res;
    }

    /**
     * 删除空目录
     * @param $dir 目录
     * @return bool
     * @throws \Exception
     */
    public function delDir($dir){
        $client = $this->client();
        // 删除空目录
        if($dir != ""){
            $res=$client->deleteDir($dir);
        }
        return $res;
    }

    /**
     * 获取图片信息
     * @param $key 图片完整地址
     * @param $http 域名
     * @return array
     */
    public function getInfo($key,$http){
        $add = str_replace ($http,'',$key);
        $add = str_replace ('http://','',$add);
        $client = $this->client();
        $res=$client->info($add);
        return $res;
    }

    /**
     * 获取服务容量
     * @return string
     * @throws \Exception
     */
    public function capacity(){
        $client = $this->client();
        $res=$client->usage();
        return $res;
    }
}