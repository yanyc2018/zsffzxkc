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
class Index extends  Base
{
    public function index(){
        $map=array(
          'status'=>1,
          'acid'=>1,
            'shoptype'=>'zsff'
        );
        $map2=array(
            'status'=>1,
            'acid'=>1,
            'pid'=>0
        );
        $ads = Db::name('ads')->where($map)->select();
        $classify=Db::name('classify')->where($map2)->select();
        $base_set=Db::name('set')
            ->where(['acid'=>1])
            ->find();
        //推荐课程
        $courses=db('coursemenu')->where(['acid'=>1,'status'=>1,'is_tj'=>1])->limit(6)->select();
        foreach ($courses as $k=>$v){
            $ksnum=db('course')->where(['acid'=>1,'status'=>1,'menuid'=>$v['id']])->count();
            $courses[$k]['ksnum']=$ksnum;
        }
        $data=array(
            'ads'=>$ads,
            'classify'=>$classify,
            'base_set'=>$base_set,
            'courses'=>$courses
        );
        $this->result(0,'获取首页数据成功',$data,0,'json');
    }
    public function indextjlist(){
        //推荐课程
        $courses=db('coursemenu')->where(['acid'=>1,'status'=>1,'is_tj'=>1])->select();
        foreach ($courses as $k=>$v){
            $ksnum=db('course')->where(['acid'=>1,'status'=>1,'menuid'=>$v['id']])->count();
            $courses[$k]['ksnum']=$ksnum;
        }
        if($courses){
            $this->result(0,'获取推荐课程数据成功',$courses,0,'json');
        }else{
            $this->result(1,'暂无推荐课程','',0,'json');
        }
    }
    public function dhhyp(){
        $post=input('post.');
        $Nowpage = input('post.page') ? input('post.page'):0;
        $limits =2;
        $limit=$Nowpage*$limits.','.$limits;
        $is_tj=$post['type']=='tj'?1:0;
        $courses=db('coursemenu')->where(['acid'=>1,'status'=>1,'is_tj'=>$is_tj])
            ->where('fenleiid','like','%'.$post['flid'].'%')
            ->limit($limit)->select();
        foreach ($courses as $k=>$v){
            $ksnum=db('course')->where(['acid'=>1,'status'=>1,'menuid'=>$v['id']])->count();
            $courses[$k]['ksnum']=$ksnum;
        }
        if($courses){
            $this->result(0,'获取导航换一批课程列表数据成功',$courses,0,'json');
        }else{
            $this->result(1,'暂无课程','',0,'json');
        }
    }
}
