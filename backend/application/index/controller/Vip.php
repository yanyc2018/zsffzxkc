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
class Vip extends  Base
{
    public function viptimeinfo(){
        $id=input('post.id');
        $map=array(
          'acid'=>1,
        );
        $data=Db::name('viptime')
            ->where('id',$id)
            ->where($map)
            ->find();
        $this->result(0,'获取vip数据成功',$data,0,'json');
    }
    public function vipinfo() {
        $post=input('post.');
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
                    $res=db('user')->where($map)->where(['id'=>$post['uid']])->update(['is_vip'=>0]);
                    $data3=array(
                        'viptype'=>$data2['name'],
                        'dqtime'=>date('Y-m-d',$starttime + (60*60*24*$data2['days']))
                    );
                    if($res){
                        $this->result(2,'VIP已到期',$data3,0,'json');
                    }
                }else{
                    $data3=array(
                        'viptype'=>$data2['name'],
                        'dqtime'=>date('Y-m-d',$starttime + (60*60*24*$data2['days']))
                    );
                    $this->result(0,'已经是'.$data2['name'],$data3,0,'json');
                }
            }
        }
        else{
            $this->result(1,'还不是VIP','',0,'json');
        }
    }
}
