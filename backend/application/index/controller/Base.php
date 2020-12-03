<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\session;
class Base  extends Controller
{
    public function _initialize()
    {

    }
    public function http_curl($url,$type,$postData = ''){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        if ($type=='post'){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    //获取token
    public function get_access_token($apptype){
        if($apptype=='H5'){
            $db=db('set_h5');
        }elseif ($apptype=='MP-WEIXIN'){
            $db=db('set_smallapp');
        }elseif ($apptype=='APP-PLUS'){
            $db=db('set_app');
        }
        $set=$db->where('acid',1)->find();
        $appID = $set['appid'];
        $appsecret=$set['appsecret'];
        $get_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appID."&secret=".$appsecret;
        $data=$this->http_curl($get_url,'','');
        return ($data);
    }
    //获取设置
    public function get_set($apptype){
        if($apptype=='H5'){
            $db=db('set_h5');
        }elseif ($apptype=='MP-WEIXIN'){
            $db=db('set_smallapp');
        }elseif ($apptype=='APP-PLUS'){
            $db=db('set_app');
        }elseif ($apptype=='BASE'){
            $db=db('set');
        }
        $set=$db->where('acid',1)->find();
        return ($set);
    }
    public function sendTpl($openid,$temid,$data = [],$url='',$topcolor = '#0000'){
        $template = [
            'touser'      => $openid,
            'template_id' => $temid,
            'url'         => $url,
            'topcolor'    => $topcolor,
            'data'        => $data
        ];
        $json_template = json_encode($template);
        $access_token = $this->get_access_token('H5');
        $access_token=json_decode($access_token,true);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token['access_token'];
        $result = $this->http_curl($url,'post',urldecode($json_template));
        $resultData = json_decode($result, true);
        return $resultData;
    }
    //直播推流构造函数
    public function getPushUrl($domain, $streamName, $key = null, $time = null){
        if($key && $time){
            $txTime = strtoupper(base_convert(strtotime($time),10,16));
            //txSecret = MD5( KEY + streamName + txTime )
            $txSecret = md5($key.$streamName.$txTime);
            $ext_str = "?".http_build_query(array(
                    "txSecret"=> $txSecret,
                    "txTime"=> $txTime
                ));
        }
        return $streamName . (isset($ext_str) ? $ext_str : "");
    }
}