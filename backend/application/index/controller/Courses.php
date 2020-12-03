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
class Courses extends  Base
{
    public function search_course(){
        $post=input('post.');
        $map=array(
            'status'=>1,
            'acid'=>1
        );
        $db=db('coursemenu');
        switch ($post['keyword']){
            case 'hot':
                $coursemenu=$db->where($map)->order('viewnum DESC')->limit(50)->select();
                break;
            case 'free':
                $coursemenu=$db->where($map)->where('price',0)->select();
                break;
            case 'new':
                $coursemenu=$db->where($map)->order('addtime DESC')->limit(50)->select();
                break;
            default:
                $coursemenu=$db->where($map)->where('menuname','like','%'.$post['keyword'].'%')->select();
                break;
        }
        if(!empty($coursemenu)){
            foreach ($coursemenu as $k=>$v){
                $tinfo=db('teacher')->where(['acid'=>1,'is_verify'=>1,'id'=>$v['tid']])->find();
                $ksnum=db('course')->where(['acid'=>1,'status'=>1,'menuid'=>$v['id']])->count();
                $coursemenu[$k]['tinfo']=$tinfo;
                $coursemenu[$k]['ksnum']=$ksnum;
            }
            $this->result(0,'搜索课程列表数据成功',$coursemenu,0,'json');
        }
    }
    public function indexgoods(){
        $post=input('post.');
        $map=array(
            'status'=>1,
            'acid'=>1
        );
        $db=db('coursemenu');
        switch ($post['goods_label']){
            case 'is_tj':
                $coursemenu=$db->where($map)->where('fenleiid','like','%'.$post['flid'].'%')->where('is_tj',1)->limit(4)->select();
                break;
            default:
                $coursemenu=$db->where($map)->where('fenleiid','like','%'.$post['flid'].'%')->where(['is_tj'=>0])->limit(6)->select();
                break;
        }
        if(!empty($coursemenu)){
            foreach ($coursemenu as $k=>$v){
                $tinfo=db('teacher')->where(['acid'=>1,'is_verify'=>1,'id'=>$v['tid']])->find();
                $ksnum=db('course')->where(['acid'=>1,'status'=>1,'menuid'=>$v['id']])->count();
                $coursemenu[$k]['tinfo']=$tinfo;
                $coursemenu[$k]['ksnum']=$ksnum;
            }
            $this->result(0,'获取一级目录课程列表数据成功',$coursemenu,0,'json');
        }
    }
    public function comment(){
        $data=input('post.');
        $data['addtime']=time();
        $data['is_verify']=0;
        $data['acid']=1;
        $res=db('comment')->insert($data);
        if($res){
            $this->result(0,'评价成功,等待审核','',0,'json');
        }
    }
    public function dingyue(){
        $post=input('post.');
        $map=array(
            'acid'=>1,
            'menuid'=>$post['menuid'],
            'goodstype'=>$post['goodstype'],
            'uid'=>$post['uid']
        );
        $dyinfo=db('dingyue')->where($map)->find();
        if(empty($dyinfo)){
            $post['is_dingyue']=1;
            $post['acid']=1;
            $res = db('dingyue')->insert($post);
            if($res){
                $this->result(0,'订阅成功',$post['is_dingyue'],0,'json');
            }
        }else{
            $is_dingyue=$dyinfo['is_dingyue']==1?0:1;
            $res=db('dingyue')->where($map)->update(['is_dingyue'=>$is_dingyue]);
            if($res){
                $this->result(0,'修改订阅成功',$is_dingyue,0,'json');
            }
        }
    }
    public function index(){

        $menuid=input('post.menuid');
        $map=array(
            'acid'=>1,
            'menuid'=>$menuid
        );
        $menuinfo=Db::name('coursemenu')
            ->where(['acid'=>1,'id'=>$menuid])
            ->find();
        $menu_pay=db('mediaorder')->where($map)->where(['is_pay'=>1,'goodstype'=>'course','sonid'=>0])->find();
        if(!empty($menu_pay)){
            $menuinfo['is_pay']=1;
        }else{
            $menuinfo['is_pay']=0;
        }
        Db::name('coursemenu')
            ->where(['acid'=>1,'id'=>$menuid])
            ->setInc('viewnum');
        $courses=Db::name('course')
            ->where($map)
            ->select();
        foreach ($courses as $k=>$v){
            $son_pay=db('mediaorder')->where($map)->where(['is_pay'=>1,'goodstype'=>'course','sonid'=>$v['id']])->find();
            if(!empty($son_pay)){
                $courses[$k]['is_pay']=1;
            }else{
                $courses[$k]['is_pay']=0;
            }
        }
        if(!empty($menuinfo)){
            $teacher=Db::name('teacher')
                ->where(['acid'=>1,'id'=>$menuinfo['tid']])
                ->find();
            unset($teacher['tpassword']);
            $comment=db('comment')->where($map)->where('is_verify',1)->order('addtime desc')->select();
            foreach ($comment as $k=>$v){
                $user=db('user')->where(['acid'=>1,'id'=>$v['uid']])->find();
                $comment[$k]['nickname']=$user['nickname'];
                $comment[$k]['avatar']=$user['avatar'];
                $comment[$k]['addtime']=date('Y-m-d H:i:s',$v['addtime']);
            }
        }
        $data=array(
            'menuinfo'=>$menuinfo,
            'courses'=>$courses,
            'teacher'=>$teacher,
            'comment'=>$comment
        );
        $dingyue=db('dingyue')->where(['acid'=>1,'menuid'=>$menuid,'goodstype'=>'course','uid'=>input('post.uid')])->find();
        if(!empty($dingyue)){
            $data['is_dingyue']=$dingyue['is_dingyue'];
        }else{
            $data['is_dingyue']=0;
        }
        $this->result(0,'获取单目录课程列表数据成功',$data,0,'json');
    }
//    课程目录或子课程详情
    public function courseinfo(){
        $menuid=input('post.menuid');
        $sonid=input('post.sonid');
        $map=array(
            'acid'=>1,
            'id'=>$menuid
        );
        $map2=array(
            'acid'=>1,
            'menuid'=>$menuid,
            'id'=>$sonid
        );
        if($sonid==''){
            Db::name('coursemenu')
                ->where($map)
                ->setInc('viewnum');
            $info=Db::name('coursemenu')
                ->where($map)
                ->find();
        }else{
            $info1=Db::name('coursemenu')
                ->where($map)
                ->find();
            $info=Db::name('course')
                ->where($map2)
                ->find();
            $info['thumb']=$info1['thumb'];
        }
        if(!empty($info)){
            $this->result(0,'获取课程信息数据成功',$info,0,'json');
        }
    }
    public function tuwendetail(){
        $twid=input('post.twid');
        $map=array(
            'acid'=>1,
            'id'=>$twid
        );
        $twinfo=Db::name('course')
            ->where($map)
            ->find();
        if(!empty($twinfo)){
            $this->result(0,'获取图文详情数据成功',$twinfo,0,'json');
        }
    }
}
