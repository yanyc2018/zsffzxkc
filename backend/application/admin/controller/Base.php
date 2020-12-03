<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Node;
use think\Db;
use think\session;
class Base  extends Controller
{
    public function _initialize()
    {
        if(!session('?uid')||!session('?username')){
            $this->redirect(url('admin/login/index'));
        }
        if(request()->controller() != 'Data' && request()->action () != 'revert') {
            $adminSta = Db::name ('admin')->where ('id' , session ('uid'))->field ('status,username')->find ();
            $roleSta = Db::name ('admin')->alias ('a')->join ('auth_group g' , 'a.groupid=g.id' , 'left')->where ('a.id' , session ('uid'))->field ('g.status,g.title')->find ();
            if ( is_null ($adminSta[ 'username' ]) ) {
                writelog (session ('username') . '账号不存在,强制下线！' , 200);
                $this->error ('抱歉，账号不存在,强制下线' , 'admin/login/loginout');
            }
            if ( is_null ($roleSta[ 'title' ]) ) {
                writelog (session ('rolename') . '身份不存在,强制下线！' , 200);
                $this->error ('抱歉，身份不存在,强制下线' , 'admin/login/loginout');
            }
            if ( $adminSta[ 'status' ] == 2 ) {
                writelog ($adminSta[ 'username' ] . '账号被禁用,强制下线！' , 200);
                $this->error ('抱歉，该账号被禁用，强制下线' , 'admin/login/loginout');
            }
            if ( $roleSta[ 'status' ] == 2 ) {
                writelog ($roleSta[ 'title' ] . '角色被禁用,强制下线！' , 200);
                $this->error ('抱歉，该账号角色被禁用，强制下线' , 'admin/login/loginout');
            }
        }

        $auth = new \com\Auth();
        $this->checksite();
        $module     = strtolower(request()->module());
        $controller = strtolower(request()->controller());
        $action     = strtolower(request()->action());
//        $server=array(
//            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
//            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
//            'SERVER_PORT'=>$_SERVER['SERVER_PORT'],
//            'OS'=>php_uname(),
//            'SERVER_VERSION'=>php_uname('s').php_uname('r'),
//            'PHP_VERSION'=>phpversion(),
//            'SERVER_TIME'=>time()
//        );
//        $url1='https://zsff.sxjiangyan.com/index.php/index/checksite';
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, $url1);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_POST, 1);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $server);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
//        $output = curl_exec($curl);
//        curl_close($curl);
        $url        = $module."/".$controller."/".$action;
        //跳过检测以及主页权限
        if(session('uid')!=1){
            foreach(config('auth_pass') as $vo){
                $pass[] = strtolower($vo);
            }
            if(!in_array($url,$pass)){
                if(!$auth->check($url,session('uid'))){
                    $this->error('抱歉，您没有操作权限');
                }
            }
        }

        //首页展示用户&菜单信息
        $node = new Node();
        $this->assign([
            'username' => session('username'),
            'portrait' => session('portrait'),
            'rolename' => session('rolename'),
            'menu' => $node->getMenu(session('rule'))
        ]);

        $config = cache('db_config_data');
        if(!$config){
            $config = api('Config/lists');
            cache('db_config_data',$config);
        }
        config($config);
        if(config('web_site_close') == 0 && session('uid') !=1 ){
            $this->error('站点已经关闭，请稍后访问~');
        }
        if(config('admin_allow_ip') && session('uid') !=1 ){
            if(in_array(request()->ip(),explode(',',config('admin_allow_ip')))){
                $this->error('403:禁止访问');
            }
        }
    }
    public function checksite(){
        $server=array(
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_PORT'=>$_SERVER['SERVER_PORT'],
            'OS'=>php_uname(),
            'SERVER_VERSION'=>php_uname('s').php_uname('r'),
            'PHP_VERSION'=>phpversion(),
            'SERVER_TIME'=>time()
        );
        $url='https://zsff.sxjiangyan.com/index.php/index/checksite';
        $this->http_curl($url,'post',$server);
    }
    /**
     * place 三级联动
     * @return \think\response\Json
     */
    public function place()
    {
        $area = new \app\common\place\Area;
        $data = $area->area();
        return json($data);
    }

    /**
     * 极光推送
     * @param $type 1:推送个人  2:推送全体
     * @param $alias 别名 user_id OR token
     * @param $message 推送消息内容
     * @param $extras 扩展字段接受数组
     * @return array
     */
    public function Jpush($type,$alias,$message,$extras)
    {
        $alias = (string)$alias;
        import('jpush.autoload', EXTEND_PATH);
        //初始化JPushClient
        $client = new \JPush\Client(config('jpush.appKey'),config('jpush.masterSecret'));
        //生成推送Payload构建器
        $push = $client->push();
        //推送平台 'all'  OR  ['ios','android']  OR  'ios','android'
        $push->setPlatform('all');
        //1:推送个人  2:推送全体
        if($type==1){
            $push->addAlias($alias);//按别名推送
        }else{
            $push->addAllAudience();//广播消息推送
        }
        //IOS推送
        $push->iosNotification($message, [
                'alert'=>$message,
                'badge' => '+1',
                'extras' => $extras,
                'sound'=>'default'
            ]
        );
        //Android推送
        $push->androidNotification($message, [
                'alert'=>$message,
                'extras' => $extras
            ]
        );
        return $push->send();
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
    public function http_curl($url,$type,$postData){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if ($type=='post'){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}