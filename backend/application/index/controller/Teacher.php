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
class Teacher extends  Base
{
    public function tinfo(){
        $uid=input('post.uid');
        $tinfo=db('teacher')->where(['acid'=>1,'uid'=>$uid])->find();
        if(!empty($tinfo)){
            $this->result(0,'获取讲师详情数据成功',$tinfo,0,'json');
        }else{
            $this->result(1,'讲师未注册','',0,'json');
        }
    }
    public function index(){
        $map=array(
            'acid'=>1,
        );
        $teachers=Db::name('teacher')
            ->where($map)
            ->select();
        $data=array(
            'teachers'=>$teachers,
        );
        $this->result(0,'获取讲师列表数据成功',$data,0,'json');
    }
    //   讲师详情
    public function detail(){
        $tid=input('post.tid');
        $map=array(
            'id'=>$tid,
            'acid'=>1,
        );
        $teacher=Db::name('teacher')
            ->where($map)
            ->find();
        $courses=db('coursemenu')->where(['acid'=>1,'tid'=>$tid])->select();
        if(!empty($courses)){
            foreach ($courses as $k=>$v){
                $ksnum=db('course')->where(['acid'=>1,'menuid'=>$v['id']])->count();
                $courses[$k]['ksnum']=$ksnum;
            }
        }
        $data=array(
            'teacher'=>$teacher,
            'courses'=>$courses
        );
        $this->result(0,'获取讲师详情数据成功',$data,0,'json');
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
    public function js_reg(){
        $map=array(
            'acid'=>1
        );
        $post=input('post.');
        $phone=$post['phone'];
        if($phone!=''){
            if (time()-cache('codetime')>300) {
                cache('code', NULL);
                $this->result(3,'验证码超时失效！',array('codetime'=>cache('codetime')),0,'json');
            }else{
                if (cache('code')!=$post['code']) {
                    $this->result(1,'验证码不正确！','',0,'json');
                }elseif (cache('phone')!=$post['phone']){
                    $this->result(1,'提交手机号非验证手机号,请重试！','',0,'json');
                }else{
                    $phone_reg=Db::name('teacher')
                        ->where($map)
                        ->where('tphone',$phone)
                        ->find();
                    $data=array(
                        'uid'=>$post['uid'],
                        'imgname'=>$post['name'],
                        'tphone'=>$post['phone'],
                        'tpassword'=>md5(substr($post['phone'],-6)),
                        'is_verify'=>0,
                        'addtime'=>time()
                    );
                    if(!empty($phone_reg)){
                        $res=Db::name('teacher')->where($map)->where('tphone',$phone)->update($data);
                        if($res){
                            $this->result(0,'请等待审核！','',0,'json');
                        }
                    }else{
                        $data['acid']=1;
                        $res=Db::name('teacher')->insert($data);
                        if($res){
                            $this->result(0,'请等待审核！','',0,'json');
                        }
                    }
                }
            }
        }
    }
}
