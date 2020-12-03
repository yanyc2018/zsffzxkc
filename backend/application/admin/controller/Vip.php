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
class Vip extends Base
{
    public function index(){
        if(request()->isAjax()) {
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = Db::name('user')
                ->where('acid',1)
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            foreach ($data as $k=>$v){
                $data[$k]['createtime']=date('Y-m-d H:i:s',$v['createtime']);
                $data[$k]['lasttime']=$v['lasttime']?date('Y-m-d H:i:s',$v['lasttime']):'';
            }
            $total=Db::name('user')->count();
            $this->result(0,'成功',$data,$total);
        }
        return $this->fetch();
    }
    public function timepart(){
        if(request()->isAjax()) {
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = Db::name('viptime')
                ->where('acid',1)
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            $total=Db::name('viptime')->count();
            $this->result(0,'成功',$data,$total);
        }
        return $this->fetch();
    }
    public function timepartadd(){
        $id=input('param.id');
        if(isset($id)){
            $item=Db::name('viptime')
                ->where('id',$id)
                ->find();
            if(!empty($item)){
                $this->assign('item',$item);
            }
        }else{
            $this->assign('item',array());
        }
        if(request()->isPost()){
            $data = input('post.');
            if($data['id']!=''){
                $res=Db::name('viptime')->where('id',$data['id'])->update($data);
                if($res){
                    if($res==0){
                        $this->result('0','无更新');
                    }else{
                        $this->result('0','修改成功');
                    }
                }else{
                    $this->result('1','修改失败');
                }
            }else{
                $data['acid']=1;
                $res=Db::name('viptime')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }

        return $this->fetch();
    }
    public function add_vip(){
        $id=input('param.id');
        if(isset($id)){
            $item=Db::name('user')
                ->where('id',$id)
                ->find();
            if(!empty($item)){
                $this->assign('item',$item);
            }
        }else{
            $this->assign('item',array());
        }
        if(request()->isPost()){
            $data = input('post.');
            unset($data['file']);
            if($data['id']!=''){
                $res=Db::name('user')->where('id',$data['id'])->update($data);
                if($res){
                    if($res==0){
                        $this->result('0','无更新');
                    }else{
                        $this->result('0','修改成功');
                    }
                }else{
                    $this->result('1','修改失败');
                }
            }else{
                $data['acid']=1;
                $data['addtime']=time();
                $data['tpassword']=md5(substr($data['tphone'],-6).$data['acid']);
                $res=Db::name('user')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }

        return $this->fetch();
    }
    public function vip_del(){
        if(request()->isAjax()) {
            $id = input('param.id');
            $res=Db::name('user')->where('id',$id)->delete();
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
