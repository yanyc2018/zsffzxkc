<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use think\Db;
use org\Qiniu;
//用户管理
class Order extends Base
{
    public function course(){
        if(request()->isAjax()) {
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = Db::name('mediaorder')
                ->where(['acid'=>1,'goodstype'=>'course'])
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            foreach ($data as $k=>$v){
                $userinfo=db('user')->where(['acid'=>1,'id'=>$v['uid']])->find();
                $goodsinfo=db('coursemenu')->where(['acid'=>1,'id'=>$v['menuid']])->find();
                $data[$k]['goodsname']=$goodsinfo['menuname'];
                $data[$k]['avatar']=$userinfo['avatar'];
                $data[$k]['nickname']=$userinfo['nickname'];
                $data[$k]['ordertime']=date('Y-m-d H:i:s',$v['ordertime']);
                $data[$k]['paytime']=$v['paytime']?date('Y-m-d H:i:s',$v['paytime']):'';
                $data[$k]['is_pay']=$v['is_pay']==1?'已支付':'未支付';
                if($v['pay_type']=='wxpay'){
                    $data[$k]['pay_type']='微信支付';
                }elseif ($v['pay_type']=='alipay'){
                    $data[$k]['pay_type']='支付宝支付';
                }elseif ($v['pay_type']=='credit'){
                    $data[$k]['pay_type']='积分兑换';
                }elseif ($v['pay_type']=='yhm'){
                    $data[$k]['pay_type']='优惠码兑换';
                }
            }
            $total=Db::name('mediaorder')
                ->where(['acid'=>1,'goodstype'=>'course'])
                ->count();
            $this->result(0,'成功',$data,$total);
        }
        return $this->fetch();
    }
    public function order_del(){
        if(request()->isAjax()) {
            $id = input('param.id');
            $res=Db::name('mediaorder')->where('id',$id)->delete();
            if($res){
                $this->result(0,'删除成功');
            }else{
                $this->result(1,'删除失败');
            }
        }
    }
    public function status_switch(){
        $id = input('param.id');
        $state = input('param.state');
        $data=array(
            'state'=>$state
        );
        $res=Db::name('user')->where('id',$id)->update($data);
        if($res){
            $this->result(200,'操作成功');
        }else{
            $this->result(500,'操作失败');
        }
    }
}
