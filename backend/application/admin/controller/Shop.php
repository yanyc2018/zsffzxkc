<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use think\Db;
use org\Qiniu;
//知识店铺
class Shop extends Base
{
    public function comment(){
        if(request()->isAjax()) {
            $map = array(
                'acid' => 1
            );
            $key=input('keyword');
            if(isset($key)&&$key!=""){
                $map['comment'] = ['like',"%" . $key . "%"];
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = Db::name('comment')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            foreach ($data as $k=>$v){
                $userinfo=db('user')->where(['acid'=>1,'id'=>$v['uid']])->find();
                $data[$k]['avatar']=$userinfo['avatar'];
                $data[$k]['nickname']=$userinfo['nickname'];
                $data[$k]['is_verify']=$v['is_verify']==1?'已审核':'未审核';
                $data[$k]['addtime']=date('Y-m-d H:i',$v['addtime']);
                if($v['goodstype']=='course'){
                    $data[$k]['goodstype']='知识课程';
                    $db=db('coursemenu');
                    $goodsinfo=$db->where(['acid'=>1,'id'=>$v['menuid']])->find();
                    $data[$k]['goodsname']=$goodsinfo['menuname'];
                }else{
                    if($v['goodstype']=='pxcourse'){
                        $data[$k]['goodstype']='培训班课程';
                        $db=db('pxcourse');
                    }elseif($v['goodstype']=='mall'){
                        $data[$k]['goodstype']='实物商品';
                        $db=db('goods');
                    }
                    $goodsinfo=$db->where(['acid'=>1,'id'=>$v['menuid']])->find();
                    $data[$k]['goodsname']=$goodsinfo['name'];
                }
            }
            $total=Db::name('comment')->where($map)->count();
            $this->result(0,'成功',$data,$total);
        }
        return $this->fetch();
    }
    public function comment_sh(){
        if(request()->isAjax()) {
            $id = input('param.id');
            $res=Db::name('comment')->where('id',$id)->setField('is_verify',1);
            if($res){
                $this->result(0,'设置成功');
            }else{
                $this->result(1,'设置失败');
            }
        }
    }
    public function notice(){
        if(request()->isAjax()) {
            $map = array(
                'acid' => 1
            );
            $key=input('keyword');
            if(isset($key)&&$key!=""){
                $map['name'] = ['like',"%" . $key . "%"];
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = Db::name('gonggao')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            foreach ($data as $k=>$v){
                $data[$k]['addtime']=date('Y-m-d H:i',$v['addtime']);
            }
            $total=Db::name('gonggao')->where($map)->count();
            $this->result(0,'成功',$data,$total);
        }
        return $this->fetch();
    }
    public function notice_add(){
        $id=input('param.id');
        if(isset($id)){
            $item=Db::name('gonggao')
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
            $data['addtime']=time();
            if($data['id']!=''){
                $res=Db::name('gonggao')->where('id',$data['id'])->update($data);
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
                $res=Db::name('gonggao')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }
        return $this->fetch();
    }
    public function notice_del(){
        if(request()->isAjax()) {
            $id = input('param.id');
            $res=Db::name('gonggao')->where('id',$id)->delete();
            if($res){
                $this->result(0,'删除成功');
            }else{
                $this->result(1,'删除失败');
            }
        }
    }
    public function ads(){
        if(request()->isAjax()) {
            $map = array(
                'acid' => 1
            );
            $key=input('keyword');
            if(isset($key)&&$key!=""){
                $map['title'] = ['like',"%" . $key . "%"];
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = Db::name('ads')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            foreach ($data as $k=>$v){
                if($v['shoptype']=='zsff'){
                    $shoptype='知识店铺';
                }elseif ($v['shoptype']=='mall'){
                    $shoptype='教辅商城';
                }elseif ($v['shoptype']=='pxjg'){
                    $shoptype='培训机构';
                }elseif ($v['shoptype']=='credit'){
                    $shoptype='积分商城';
                }
                else{
                    $shoptype='';
                }
                $data[$k]['shoptype_name']=$shoptype;
            }

            $total=Db::name('ads')->where($map)->count();
            $this->result(0,'成功',$data,$total);
        }
        return $this->fetch('shop/ads');
    }
    public function ads_add(){
        $id=input('param.id');
        if(isset($id)){
            $item=Db::name('ads')
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
                $data['status']=$data['status']?$data['status']:0;
                $res=Db::name('ads')->where('id',$data['id'])->update($data);
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
                $data['status']=1;
                $data['acid']=1;
                $res=Db::name('ads')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }
        return $this->fetch('shop/add_ads');
    }
    public function ads_del(){
        if(request()->isAjax()) {
            $id = input('param.id');
            $res=Db::name('ads')->where('id',$id)->delete();
            if($res){
                $this->result(0,'删除成功');
            }else{
                $this->result(1,'删除失败');
            }
        }
    }
    public function status_switch(){
        $id = input('param.id');
        $status = input('param.status');
        $data=array(
            'status'=>$status
        );
        $res=Db::name('ads')->where('id',$id)->update($data);
        if($res){
            $this->result(200,'操作成功');
        }else{
            $this->result(500,'操作失败');
        }
    }
}
