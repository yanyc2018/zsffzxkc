<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\index\controller;
use app\index\controller\Base;
use think\Controller;
use think\Db;
use think\Session;
use think\Cache;
use aldy\SmsDemo;
class User extends  Base
{
    // 学习时间兑换积分
    public function studytime_dh()
    {
        $map=array(
            'acid'=>1
        );
        $post=input('post.');
        $setting=Db::name('set')->where($map)->find();
        $bl=(int)($setting['dh_score']/$setting['study_minute']);
        $dh_score=$bl*$post['minute'];
        $map2=array(
            'acid'=>1,
            'uid'=>$post['uid'],
            'date'=>date('Ymd',time())
        );

        $res=db('user')->where($map)->where('id',$post['uid'])->setInc('credit',$dh_score);
        if($res){
            db('study_time')->where($map2)->setInc('dh_score',$dh_score);
            $this->result(0,'兑换积分成功','',0,'json');
        }
    }
    // 今天学习时间查询
    public function my_today_studytime(){
        $post=input('post.');
        $map2=array(
            'acid'=>1,
            'uid'=>$post['uid'],
            'date'=>date('Ymd',time())
        );
        $todayinfo=db('study_time')->where($map2)->find();
        if($todayinfo){
            $this->result(0,'查询今天学习时间成功',$todayinfo,0,'json');
        }
    }
    // 累计学习时间统计
    public function my_studytime(){
        $post=input('post.');
        $map=array(
            'acid'=>1,
            'uid'=>$post['uid']
        );
        $studyinfo=db('study_time')->where($map)->select();
        $vtime=0;
        $atime=0;
        $tutime=0;
        if(!empty($studyinfo)){
            foreach ($studyinfo as $k=>$v){
                $vtime+=$v['videostudytime'];
                $atime+=$v['audiostudytime'];
                $tutime+=$v['tuwenstudytime'];
            }
        }
        $map2=array(
            'acid'=>1,
            'uid'=>$post['uid'],
            'date'=>date('Ymd',time())
        );
        $todayinfo=db('study_time')->where($map2)->find();
        $data=array(
            'vtime'=>$vtime,
            'atime'=>$atime,
            'tutime'=>$tutime,
            'todayinfo'=>$todayinfo
        );
        $this->result(0,'查询累计学习时间成功',$data,0,'json');
    }
    // 保存学习时长
    public function save_studytime(){
        $post=input('post.');
        $map=array(
            'acid'=>1,
            'uid'=>$post['uid'],
            'date'=>date('Ymd',time())
        );
        $studyinfo=db('study_time')->where($map)->find();
        switch ($post['media']){
            case 'video':
                if(empty($studyinfo)){
                    $data=array(
                        'videostudytime'=>$post['studytime'],
                        'date'=>date('Ymd',time()),
                        'acid'=>1,
                        'uid'=>$post['uid'],
                    );
                    $res=db('study_time')->insert($data);
                }else{
                    $res=db('study_time')->where($map)->setField('videostudytime',$post['studytime']);
                }
                break;
            case 'audio':
                if(empty($studyinfo)){
                    $data=array(
                        'audiostudytime'=>$post['studytime'],
                        'date'=>date('Ymd',time()),
                        'acid'=>1,
                        'uid'=>$post['uid'],
                    );
                    $res=db('study_time')->insert($data);
                }else{
                    $res=db('study_time')->where($map)->setField('audiostudytime',$post['studytime']);
                }
                break;
            case 'tuwen':
                if(empty($studyinfo)){
                    $data=array(
                        'tuwenstudytime'=>$post['studytime'],
                        'date'=>date('Ymd',time()),
                        'acid'=>1,
                        'uid'=>$post['uid'],
                    );
                    $res=db('study_time')->insert($data);
                }else{
                    $res=db('study_time')->where($map)->setField('tuwenstudytime',$post['studytime']);
                }
                break;
        }
        if($res){
            $this->result(0,'保存或更新学习时间成功','',0,'json');
        }
    }
    //我的课程
    public function my_course(){
        $map=array(
            'acid'=>1
        );
        $post=input('post.');
        $mycourse=Db::name('mediaorder')
            ->where($map)
            ->where(['goodstype'=>$post['goodstype'],'uid'=>$post['uid'],'is_pay'=>1])
            ->order('ordertime','desc')
            ->select();
        if(!empty($mycourse)){
            foreach ($mycourse as $k=>$v){
                if($post['goodstype']=='course') {
                    $menuinfo = db('coursemenu')->where($map)->where(['id' => $v['menuid'], 'media' => $v['media']])->find();
                    if (!empty($menuinfo)) {
                        $mycourse[$k]['menuinfo'] = $menuinfo;
                    }
                }else if($post['goodstype']=='mall'){
                    $menuinfo = db('goods')->where($map)->where(['id' => $v['menuid']])->find();
                    if (!empty($menuinfo)) {
                        $mycourse[$k]['menuinfo'] = $menuinfo;
                    }
                }else if($post['goodstype']=='pxcourse'){
                    $menuinfo = db('pxcourse')->where($map)->where(['id' => $v['menuid']])->find();
                    if (!empty($menuinfo)) {
                        $mycourse[$k]['menuinfo'] = $menuinfo;
                    }
                }
            }
            $this->result(0,'获取我的订单成功',$mycourse,0,'json');
        }else{
            $this->result(1,'暂无订单','',0,'json');
        }
    }
    //我的订阅
    public function my_dingyue(){
        $post=input('post.');
        $map=array(
            'acid'=>1,
        );
        $mycourse=Db::name('dingyue')
            ->where($map)
            ->where('goodstype',$post['goodstype'])
            ->where(['uid'=>$post['uid'],'is_dingyue'=>1])
            ->select();
        if(!empty($mycourse)){
            foreach ($mycourse as $k=>$v){
                if($post['goodstype']=='course'){
                    $menuinfo=db('coursemenu')->where($map)->where(['id'=>$v['menuid'],'media'=>$v['media']])->find();
                    if(!empty($menuinfo)){
                        $mycourse[$k]['menuinfo']=$menuinfo;
                    }
                }else if($post['goodstype']=='mall'){
                    $menuinfo=db('goods')->where($map)->where(['id'=>$v['menuid']])->find();
                    if(!empty($menuinfo)){
                        $mycourse[$k]['menuinfo']=$menuinfo;
                    }
                }
            }
            $this->result(0,'获取我的订阅课程成功',$mycourse,0,'json');
        }else{
            $this->result(1,'暂无我的订阅','',0,'json');
        }
    }
    //    打卡获积分
    public function daka_jifen(){
        $map=array(
            'acid'=>1
        );
        $post=input('post.');
        $setting=Db::name('set')->where($map)->find();
        $qdjf=$setting['qdjf_bl']!=''?$setting['qdjf_bl']:1;
        $data=array(
            'acid'=>1,
            'uid'=>$post['uid'],
            'qdtime'=>time()
        );
        $res2=Db::name('qiandao')->insert($data);
        $user =Db::name('user')->where($map)->where('id',$post['uid'])->find();
        $res=Db::name('user')->where($map)->where('id',$post['uid'])->update(array('credit'=>$user['credit']+$qdjf));
        if($res && $res2){
            $user1 =Db::name('user')->where($map)->where('id',$post['uid'])->find();
            $data=array(
                'credit'=>$user1['credit'],
                'qdjf'=>$qdjf
            );
            $this->result(0,'签到成功',$data,0,'json');
        }
    }
    //签到打卡
    public function daka(){
        $map=array(
          'acid'=>1
        );
        $post=input('post.');
        $thismonth = date('m');
        $thisyear = date('Y');
        $startDay = $thisyear . '-' . $thismonth . '-1';
        $endDay = $thisyear . '-' . $thismonth . '-' . date('t', strtotime($startDay));
        $b_time  = strtotime($startDay);
        $e_time  = strtotime($endDay);
        $total =Db::name('qiandao')
            ->where($map)
            ->where('uid',$post['uid'])
            ->where('qdtime','gt',$b_time)
            ->where('qdtime','lt',$e_time)
            ->count();
        $setting=Db::name('set')->where($map)->find();
        $lastdk=Db::name('qiandao')
            ->where($map)
            ->where('uid',$post['uid'])
            ->order('qdtime desc')
            ->limit(1)
            ->select();
        if(!empty($lastdk)){
            $lastdk_days=date('d',$lastdk[0]['qdtime']);
            $today=date('d',time());
            $is_qd=$lastdk_days!=$today?0:1;
            $data=array(
                'total'=>$total,
                'setting'=>$setting,
                'is_qd'=>$is_qd
            );
            $this->result(0,'签到页数据获取成功',$data,0,'json');
        }else{
            $this->result(1,'暂无签到记录','',0,'json');
        }

    }
    //vip周期
    public function  viptime(){
        $data=Db::name('viptime')->where(['acid'=>1])->select();
        if(!empty($data)){
            $this->result(0,'获取会员周期列表成功',$data,0,'json');
        }
    }
    //H5微信坐标逆解析
    public function  get_location(){
        $post=input('post.');
        $url="https://apis.map.qq.com/ws/geocoder/v1/?coord_type=5&get_poi=0&output=json&key=您的腾讯地图key&location=".$post['lat'].','.$post['lng'];
        $data=$this->http_curl($url,'','');
        $data1=json_decode($data,true);
        $result=$data1['result'];
        $this->result(0,'获取位置信息成功',$result,0,'json');
    }

    //通过token获取jsapi_ticket(票据)
    private function get_jsapi_ticket()
    {
        $access_token = $this->get_access_token('H5');
        $access_token=json_decode($access_token,true);
        $get_ticket_url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $access_token['access_token'] . "&type=jsapi";
        $data=$this->http_curl($get_ticket_url,'','');
        return ($data);
    }
    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    //返回配置config数据
    public function  get_sign(){
        $post=input('post.');
        //签名算法
        $set=db('set_h5')->where('acid',1)->find();
        $appID = $set['appid'];
        $timestamp=time();  //时间戳
        $url=$post['url']; //当前访问url
        $jsapi_ticket=$this->get_jsapi_ticket();
        $jsapi_ticket=json_decode($jsapi_ticket,true);
        $noncestr =$this->createNonceStr();
        //拼接$signature原型
        $ping="jsapi_ticket=".$jsapi_ticket['ticket']."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$url;
        //加密生成signature
        $signature=sha1($ping);
        $signPackage = array(
            "appId" => $appID,
            "nonceStr" => $noncestr,
            "timestamp"=> $timestamp,
            "signature"=> $signature
        );
        if(!empty($signPackage)){
            $this->result(0,'获取签名包成功',$signPackage,0,'json');
        }
    }
    //    微信小程序code换取openid
    public function wxapp_code(){
        $code=input('post.code');
        $apptype=input('post.apptype');
        if($apptype=='MP-WEIXIN'){
            $db=Db::name('set_smallapp');
        }
        $map=array(
            'acid'=>1
        );
        $set=$db->where($map)->find();
        $appid = $set['appid'];
        $secret= $set['appsecret'];
        if($apptype=='MP-WEIXIN'){
            $url='https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        if($output){
            echo $output;

        }
    }
    public function gzh_code(){
        $map=array(
            'acid'=>1
        );
        $db=Db::name('set_h5');
        $set=$db->where($map)->find();
        $post=input('post.');
        $appinfo['key']=$set['appid'];
        $appinfo['secret']=$set['appsecret'];
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appinfo['key'].'&secret='.$appinfo['secret'].'&code='.$post['code'].'&grant_type=authorization_code';
        $data=$this->http_curl($url,'','');
        $data1=json_decode($data,true);
//        $this->result(0,'检查信息',$data1,0,'json');
//        die;
        $token = $data1['access_token'];
        $openid=$data1['openid'];
        $unionid=$data1['unionid']?$data1['unionid']:'';
        $set=$this->get_set('BASE');
        if($unionid!=''){
            $map=array(
                'acid'=>1,
                'unionid'=>$unionid
            );
        }else{
            $map=array(
                'acid'=>1,
                'gzh_openid'=>$openid
            );
        }
        $userdata=Db::name('user')
            ->where($map)
            ->find();
        $pid=$post['pid'];
        if(!empty($userdata)){
            $url1="https://api.weixin.qq.com/sns/userinfo?access_token={$token}&openid={$openid}&lang=zh_CN";
            $type='get';
            $userinfo=json_decode($this->http_curl($url1,$type,''),true);
            //var_dump($userinfo);die;
            $data3 = array(
                'nickname' =>$userinfo['nickname'],
                'avatar' =>$userinfo['headimgurl'],
                'gender'=>$userinfo['sex'],
                'gzh_openid'=>$userinfo['openid'],
                'unionid'=>$unionid,
            );
            if($pid!=0 && $pid!=$userdata['id'] && $userdata['pid']==0 && $userdata['is_lock']==0){
                $data3['pid']=$pid;
                $sjinfo=db('user')->where(['acid'=>1,'id'=>$pid])->find();
                $data3['path']=$sjinfo['path'].'-'.$pid;
                $data3['plv']=$sjinfo['plv']+1;
            }
            $res = Db::name('user')
                ->where($map)
                ->update($data3);
            $userdata=Db::name('user')
                ->where($map)
                ->find();
        }else{
            if (isset($openid) && isset($token)) {
                $url1="https://api.weixin.qq.com/sns/userinfo?access_token={$token}&openid={$openid}&lang=zh_CN";
                $type='get';
                $userinfo=json_decode($this->http_curl($url1,$type,''),true);
                $data3 = array(
                    'acid'=>1,
                    'gzh_openid'=>$userinfo['openid'],
                    'nickname' =>$userinfo['nickname'],
                    'avatar' =>$userinfo['headimgurl'],
                    'gender'=>$userinfo['sex'],
                    'createtime'=>time(),
                    'is_fxs'=>$set['fx_auto']==1?1:0,
                    'unionid'=>$unionid,
                    'apptype'=>'H5'
                );

                if($pid!=0){
                    $data3['pid']=$pid;
                    $sjinfo=db('user')->where(['acid'=>1,'id'=>$pid])->find();
                    $data3['path']=$sjinfo['path'].'-'.$pid;
                    $data3['plv']=$sjinfo['plv']+1;
                }
                $res = Db::name('user')
                    ->insert($data3);
                $userId = Db::name('user')->getLastInsID();
                $userdata=Db::name('user')
                    ->where('id',$userId)
                    ->find();
            }
        }
        unset($userdata['password']);
        $userdata['uid']=$userdata['id'];
        $userdata['token']=md5($userdata['id'].$userdata['phone']);

        if($res){
            if($pid!=0) {
                db('user')->where(['acid' => 1, 'id' => $pid])->update(['is_lock' => 1]);
            }
            if(!empty($sjinfo) && $sjinfo['gzh_openid']!=''){
                $msg = array(
                    'first' => array(
                        'value' => "恭喜您成功邀请一位新成员加入",
                        'color' => '#ff510'
                    ),
                    'keyword1' => array(
                        'value' => $userdata['nickname'],
                        'color' => '#ff510'
                    ),
                    'keyword2' => array(
                        'value' => date('Y-m-d H:i:s',time()),
                        'color' => '#ff510'
                    ),
                    'remark' => array(
                        'value' =>  '您的好友进行消费时，您将获得佣金',
                        'color' => '#ff510'
                    ),
                );
                $this->sendTpl($sjinfo['gzh_openid'],$set['msg_newuser'],$msg);
            }
            $this->result(0,'用户数据更新或新增成功',$userdata,0,'json');
        }elseif ($res==0){
            $this->result(1,'用户数据无更新',$userdata,0,'json');
        }else{
            $this->result(1,'用户数据更新或新增失败','',0,'json');
        }
    }
    public function update_userinfo(){
        $data=input('post.');
        $unionid=$data['unionid']?$data['unionid']:'';
        $map=array(
            'acid'=>1
        );
        $apptype=input('post.apptype');
        if($unionid!=''){
            $map['unionid']=$unionid;
        }else{
            if($apptype=='MP-WEIXIN'){
                $map['openid']=$data['openid'];
            }
        }
        unset($data['uid']);
        $userdata=Db::name('user')
            ->where($map)
            ->find();
        $pid=$data['pid'];
        $set=$this->get_set('BASE');
        if(!empty($userdata)){
            if($pid!=0 && $pid!=$userdata['id'] && $userdata['pid']==0 && $userdata['is_lock']==0){
                $data3=array();
                $data3['pid']=$pid;
                $sjinfo=db('user')->where(['acid'=>1,'id'=>$pid])->find();
                $data3['path']=$sjinfo['path'].'-'.$pid;
                $data3['plv']=$sjinfo['plv']+1;
                $data=array_merge($data,$data3);
            }
            $res=Db::name('user')
                ->where($map)
                ->update($data);
            $userdata=Db::name('user')
                ->where($map)
                ->find();
        }else{

            $data3 = array(
                'acid'=>1,
                'createtime'=>time(),
                'is_fxs'=>$set['fx_auto']==1?1:0,
                'unionid'=>$unionid,
            );
            if($pid!=0){
                $data3['pid']=$pid;
                $sjinfo=db('user')->where(['acid'=>1,'id'=>$pid])->find();
                $data3['path']=$sjinfo['path'].'-'.$pid;
                $data3['plv']=$sjinfo['plv']+1;
            }
            $data4=array_merge($data,$data3);
            $res = Db::name('user')
                ->insert($data4);
            $userId = Db::name('user')->getLastInsID();
            $userdata=Db::name('user')
                ->where('id',$userId)
                ->find();
        }
        unset($userdata['password']);
        $userdata['uid']=$userdata['id'];
        $userdata['token']=md5($userdata['id'].$userdata['phone']);
        if($res){
            if($pid!=0) {
                db('user')->where(['acid' => 1, 'id' => $pid])->update(['is_lock' => 1]);
            }
            if(!empty($sjinfo) && $sjinfo['gzh_openid']!=''){
                $msg = array(
                    'first' => array(
                        'value' => "恭喜您成功邀请一位新成员加入",
                        'color' => '#ff510'
                    ),
                    'keyword1' => array(
                        'value' => $userdata['nickname'],
                        'color' => '#ff510'
                    ),
                    'keyword2' => array(
                        'value' => date('Y-m-d H:i:s',time()),
                        'color' => '#ff510'
                    ),
                    'remark' => array(
                        'value' =>  '您的好友进行消费时，您将获得佣金',
                        'color' => '#ff510'
                    ),
                );
                $this->sendTpl($sjinfo['gzh_openid'],$set['msg_newuser'],$msg);
            }
            $this->result(0,'用户数据更新或新增成功',$userdata,0,'json');
        }elseif ($res==0){
            $this->result(1,'用户数据无更新',$userdata,0,'json');
        }else{
            $this->result(1,'用户数据更新或新增失败','',0,'json');
        }
    }
    //  APP微信授权登录
    public function app_wxauth(){
        $post=input('post.');
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$post['token'].'&openid='.$post['openid'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        $map=array(
            'acid'=>1
        );
        if($output){
            $userinfo=json_decode($output,true);
            $unionid=$userinfo['unionid'];
            if($unionid!=''){
                $map['unionid']=$unionid;
            }else{
                $map['app_openid']=$post['openid'];
            }
            $userdata=Db::name('user')
                ->where($map)
                ->find();
            $pid=$post['pid'];
            $set=$this->get_set('BASE');
            if(!empty($userdata)){
                $data3 = array(
                    'app_openid'=>$userinfo['openid'],
                    'nickname' =>$userinfo['nickname'],
                    'avatar' =>$userinfo['headimgurl'],
                    'gender'=>$userinfo['sex'],
                    'unionid'=>$userinfo['unionid']
                );
                if($pid!=0 && $pid!=$userdata['id'] && $userdata['pid']==0 && $userdata['is_lock']==0){
                    $data3['pid']=$pid;
                    $sjinfo=db('user')->where(['acid'=>1,'id'=>$pid])->find();
                    $data3['path']=$sjinfo['path'].'-'.$pid;
                    $data3['plv']=$sjinfo['plv']+1;
                }
                $res=Db::name('user')
                    ->where($map)
                    ->update($data3);
                $userdata=Db::name('user')
                    ->where($map)
                    ->find();
            }else{

                $data3 = array(
                    'acid'=>1,
                    'app_openid'=>$userinfo['openid'],
                    'nickname' =>$userinfo['nickname'],
                    'avatar' =>$userinfo['headimgurl'],
                    'gender'=>$userinfo['sex'],
                    'createtime'=>time(),
                    'is_fxs'=>$set['fx_auto']==1?1:0,
                    'unionid'=>$userinfo['unionid'],
                    'apptype'=>'APP-PLUS'
                );
                if($pid!=0){
                    $data3['pid']=$pid;
                    $sjinfo=db('user')->where(['acid'=>1,'id'=>$pid])->find();
                    $data3['path']=$sjinfo['path'].'-'.$pid;
                    $data3['plv']=$sjinfo['plv']+1;
                }
                $res = Db::name('user')
                    ->insert($data3);
                $userId = Db::name('user')->getLastInsID();
                $userdata=Db::name('user')
                    ->where('id',$userId)
                    ->find();
            }
            unset($userdata['password']);
            $userdata['uid']=$userdata['id'];
            $userdata['token']=md5($userdata['id'].$userdata['phone']);
            if($res){
                if($pid!=0) {
                    db('user')->where(['acid' => 1, 'id' => $pid])->update(['is_lock' => 1]);
                }
                if(!empty($sjinfo) && $sjinfo['gzh_openid']!=''){
                    $msg = array(
                        'first' => array(
                            'value' => "恭喜您成功邀请一位新成员加入",
                            'color' => '#ff510'
                        ),
                        'keyword1' => array(
                            'value' => $userdata['nickname'],
                            'color' => '#ff510'
                        ),
                        'keyword2' => array(
                            'value' => date('Y-m-d H:i:s',time()),
                            'color' => '#ff510'
                        ),
                        'remark' => array(
                            'value' =>  '您的好友进行消费时，您将获得佣金',
                            'color' => '#ff510'
                        ),
                    );
                    $this->sendTpl($sjinfo['gzh_openid'],$set['msg_newuser'],$msg);
                }
                $this->result(0,'用户数据更新或新增成功',$userdata,0,'json');
            }elseif ($res==0){
                $this->result(1,'用户数据无更新',$userdata,0,'json');
            }else{
                $this->result(1,'用户数据更新或新增失败','',0,'json');
            }
        }
    }

    public function index(){
        //Session::set('codetime',time());
        //Session::delete('codetime');
        $map=array(
            'acid'=>1,
        );
        $teachers=Db::name('teacher')
            ->where($map)
            ->select();
        $data=array(
            'teachers'=>session('codetime'),
        );
        $this->result(0,'获取讲师列表数据成功',$data,0,'json');
    }
    public function sendcode(){
        $Sms= new SmsDemo();
        $phoneNum=input('post.phone');
        $code = rand(100000,999999);
        $signName="知识付费在线课程";
        $TemplateCode=config('alidy.TemplateCode');
        $data = $Sms->sendSms($phoneNum,$signName,$TemplateCode,$code);
        if($data){
            $options = [
                // 缓存类型为File
                'type'   => 'File',
                // 缓存有效期为永久有效
                'expire' => 3600,
                // 指定缓存目录
                'path'   => APP_PATH . 'runtime/cache/',
            ];
            cache($options);
            cache('code', $code);
            cache('phone', $phoneNum);
            cache('codetime', time());
            $this->result(0,'发送成功',$data,0,'json');
        }
    }
    // 会员注册
    public function reg(){
        $m = Db::name('user');
        $post = input('post.');
        if (time()-cache('codetime')>300) {
            cache('code', NULL);
            $this->result(3,'验证码超时失效！',array('codetime'=>cache('codetime')),0,'json');
        }else{
            if (cache('code')!=$post['code']) {
                $this->result(1,'验证码不正确！','',0,'json');
            }elseif (cache('phone')!=$post['phone']){
                $this->result(1,'提交手机号非验证手机号,请重试！','',0,'json');
            }
            else{
                if ($m->where('acid',1)->where('phone',$post['phone'])->find()) {
                    $this->result(2,'此手机号已注册过！','',0,'json');
                }else{
                    $set=$this->get_set('BASE');
                    $data=array(
                        'acid'=>1,
                        'password'=>md5($post['password'].substr($post['phone'],-6)),
                        'phone'=>$post['phone'],
                        'nickname'=>'zsff_'.$post['phone'],
                        'createtime'=>time(),
                        'is_fxs'=>$set['fx_auto']==1?1:0,
                    );
                    $r = $m->insert($data);
                    if($r){
                        $this->result(0,'注册成功！','',0,'json');
                    }else{
                        $this->result(4,'注册失败！','',0,'json');
                    }
                }
            }
        }
    }
    //    更新本地缓存
    public function update_userdata(){
        $m = Db::name('user');
        $post = input('post.');
        $pid=$post['pid'];
        $userdata=$m->where(['acid'=>1,'id'=>$post['uid']])->find();
        if(!empty($userdata)){
            if($pid!=0 && $pid!=$userdata['id'] && $userdata['pid']==0 && $userdata['is_lock']==0){
                $sjinfo=db('user')->where(['acid'=>1,'id'=>$pid])->find();
                if(!empty($sjinfo)){
                    $data3=array();
                    $data3['pid']=$pid;
                    $data3['path']=$sjinfo['path'].'-'.$pid;
                    $data3['plv']=$sjinfo['plv']+1;
                    $res=Db::name('user')
                        ->where(['acid'=>1,'id'=>$post['uid']])
                        ->update($data3);
                    $userdata=$m->where(['acid'=>1,'id'=>$post['uid']])->find();
                }
            }
            unset($userdata['password']);
            $userdata['uid']=$userdata['id'];
            $userdata['token']=md5($userdata['id'].$userdata['phone']);
            $this->result(0,'资料获取成功！',$userdata,0,'json');
        }
    }
    // 绑定手机号
    public function bindphone(){
        $m = Db::name('user');
        $post = input('post.');
        if (time()-cache('codetime')>300) {
            cache('code', NULL);
            $this->result(3,'验证码超时失效！',array('codetime'=>cache('codetime')),0,'json');
        }else{
            if (cache('code')!=$post['code']) {
                $this->result(1,'验证码不正确！','',0,'json');
            }elseif (cache('phone')!=$post['phone']){
                $this->result(1,'提交手机号非验证手机号,请重试！','',0,'json');
            }
            else{
                if ($m->where('acid',1)->where('phone',$post['phone'])->find()) {
                    $this->result(2,'此手机号已注册过！','',0,'json');
                }else{
                    $data=array(
                        'phone'=>$post['phone'],
                    );
                    $r = $m->where('id',$post['uid'])->update($data);
                    if($r){
                        $this->result(0,'绑定成功！','',0,'json');
                    }else{
                        $this->result(4,'绑定失败！','',0,'json');
                    }
                }
            }
        }
    }
    // 会员登录
    public function login(){
        $post = input('post.');
        $m = Db::name('user');
        $userinfo=$m->where('phone',$post['phone'])->find();
        if(!empty($userinfo)){
            if($userinfo['status']==0){
                if($userinfo['password']!=md5($post['password'].substr($post['phone'],-6))){
                    $this->result(1,'密码不正确,请重试！','',0,'json');
                }else{
                    $data=array(
                        'uid'=>$userinfo['id'],
                        'phone'=>$userinfo['phone'],
                        'token'=>md5($userinfo['id'].$userinfo['phone'])
                    );
                    $this->result(0,'登录成功！',$data,0,'json');
                }
            }else{
                $this->result(1,'黑名单用户,禁止访问！','',0,'json');
            }
        }else{
            $this->result(1,'用户不存在,请重试！','',0,'json');
        }
    }
    //会员登录状态检查
    public function check_user(){
        $post = input('post.');
        $m = Db::name('user');
        $userinfo=$m->where('acid',1)->where('id',$post['uid'])->find();
        if(!empty($userinfo)){
            db('user')->where('acid',1)->where('id',$post['uid'])->setField('lasttime',time());
            if($userinfo['status']==0) {
                if($userinfo['is_vip']==1){
                    $map=array(
                        'acid'=>1,
                    );
                    $data = db('user')->where($map)->where(['id'=>$post['uid'],'is_vip'=>1])->find();
                    if(!empty($data) && $data['viptypeid'] !=''){
                        if($data['vipsj_orderid']!=''){
                            $data1=db('mediaorder')->where($map)->where('orderid',$data['vipsj_orderid'])->find();
                        }
                        $data2=db('viptime')->where($map)->where('id',$data['viptypeid'])->find();
                        $starttime=$data['vipsj_time']?$data['vipsj_time']:$data1['ordertime'];
                        if($data2['days']!=0){
                            if(time() > ($starttime + (60*60*24*$data2['days']))){
                                db('user')->where($map)->where(['id'=>$post['uid']])->update(['is_vip'=>0]);
                            }
                        }
                    }
                }
                if ($post['token'] != md5($userinfo['id'] . $userinfo['phone'])) {
                    $this->result(400,'token验证失败,请重试！','',0,'json');
                }else{
                    unset($userinfo['password']);
                    $this->result(200,'用户校验成功！',$userinfo,0,'json');
                }
            }elseif($userinfo['status']==1){
                $this->result(400,'黑名单用户,禁止访问！','',0,'json');
            }
        }else{
            $this->result(400,'用户不存在,请重试！','',0,'json');
        }
    }
}
