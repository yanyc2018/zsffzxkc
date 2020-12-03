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
class Credit extends  Base
{
    public function index(){
        $map=array(
          'acid'=>1,
        );
        $data=Db::name('ads')
            ->where($map)
            ->where(['shoptype'=>'credit','status'=>1])
            ->select();
        $goods=db('credit')
            ->where(['acid'=>1,'status'=>1])
            ->select();
        foreach ($goods as $k=>$v){
            if($v['goodstype']=='course'){
                $db=db('coursemenu');
            }elseif($v['goodstype']=='mall'){
                $db=db('goods');
            }elseif($v['goodstype']=='pxb'){
                $db=db('pxcourse');
            }
            $goods[$k]['goodsinfo']=$db->where($map)->where('id',$v['goodsid'])->find();
        }
        $result=array(
            'ads'=>$data,
            'goods'=>$goods
        );
        $this->result(0,'获取首页数据成功',$result,0,'json');
    }
    public function creditinfo()
    {
        $post=input('post.');
        if($post['goodstype']=='pxcourse'){
            $post['goodstype']='pxb';
        }
        $map = array(
            'acid' => 1,
            'status'=>1,
            'goodsid'=>$post['id'],
            'goodstype'=>$post['goodstype'],
        );
        $goodsinfo=db('credit')->where($map)->find();
        $this->result(0,'获取积分商品数据成功',$goodsinfo,0,'json');
    }
}
