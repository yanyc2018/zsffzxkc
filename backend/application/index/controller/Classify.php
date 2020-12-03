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
class Classify extends  Base
{
    public function index(){
        $flid=input('post.flid');
        $map=array(
          'status'=>1,
          'acid'=>1,
        );
        $son_fl=Db::name('classify')
            ->where('pid',$flid)
            ->where($map)
            ->select();

        $data=array(
            'son_fl'=>$son_fl
        );
        $this->result(0,'获取分类详情页数据成功',$data,0,'json');
    }
    public function datalist(){
        $post=input('post.');
        if($post['goodstype']=='course'){
            $db=db('classify');
        }elseif ($post['goodstype']=='mall'){
            $db=db('goods_classify');
        }
        $map=array(
            'status'=>1,
            'acid'=>1,
            'pid'=>0
        );
        $fls=$db->where($map)->select();
        $this->result(0,'获取主分类列表页数据成功',$fls,0,'json');
    }
    public function sonfldata(){
        $post=input('post.');
        if($post['goodstype']=='course'){
            $db=db('classify');
        }elseif ($post['goodstype']=='mall'){
            $db=db('goods_classify');
        }
        $map=array(
            'status'=>1,
            'acid'=>1,
            'pid'=>$post['flid']
        );
        $fls=$db->where($map)->select();
        $this->result(0,'获取子分类列表页数据成功',$fls,0,'json');
    }
    public function sonfl_goodslist(){
        $post=input('post.');
        if($post['goodstype']=='course'){
            $db=db('coursemenu');
        }elseif ($post['goodstype']=='mall'){
            $db=db('goods');
        }
        $map=array(
            'status'=>1,
            'acid'=>1,
        );
        $coursemenu=$db
            ->where('fenleiid','like','%'.$post['flid'].','.$post['sonflid'].'%')
            ->where($map)
            ->select();
        if ($post['goodstype']=='course'){
            foreach ($coursemenu as $k=>$v){
                $tinfo=db('teacher')->where(['acid'=>1,'is_verify'=>1,'id'=>$v['tid']])->find();
                $ksnum=db('course')->where(['acid'=>1,'status'=>1,'menuid'=>$v['id']])->count();
                $coursemenu[$k]['tinfo']=$tinfo;
                $coursemenu[$k]['ksnum']=$ksnum;
            }
        }
        if ($post['goodstype']=='mall'){
            foreach ($coursemenu as $k=>$v){
                $thumb=explode(',',$v['thumb']);
                $coursemenu[$k]['main_thumb']=$thumb[0];
            }
        }
        $this->result(0,'获取子分类商品数据成功',$coursemenu,0,'json');
    }
}
