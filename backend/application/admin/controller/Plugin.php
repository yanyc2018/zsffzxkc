<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use think\Db;
use org\Qiniu;
//插件管理
class Plugin extends Base
{
    public function status_switch(){
        $id = input('param.id');
        $db=input('param.type');
        $table=db($db);
        $status = input('param.status');
        $data=array(
            'status'=>$status
        );
        $res=$table->where('id',$id)->update($data);
        if($res){
            $this->result(200,'操作成功');
        }else{
            $this->result(500,'操作失败');
        }
    }
    public function search_goods(){
        if(request()->isAjax()) {
            $goodstype=input('goodstype');
            if($goodstype=='course'){
                $db=db('coursemenu');
            }elseif ($goodstype=='mall'){
                $db=db('goods');
            }elseif ($goodstype=='pxb'){
                $db=db('pxcourse');
            }
            $map=array(
                'acid'=>1
            );
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = $db
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            $total=$db
                ->where($map)
                ->count();
            $this->result(0,'成功',$data,$total);
        }
    }
    public function credit(){
        if(request()->isAjax()) {
            $map=array(
                'acid'=>1
            );
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = Db::name('credit')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            foreach ($data as $k=>$v){
                if($v['goodstype']=='course'){
                    $data[$k]['goodstype']='知识课程';
                }elseif($v['goodstype']=='mall'){
                    $data[$k]['goodstype']='实物商品';
                }elseif($v['goodstype']=='pxb'){
                    $data[$k]['goodstype']='培训班课程';
                }
                $data[$k]['addtime']=date('Y-m-d H:i:s',$v['addtime']);
            }
            $total=Db::name('credit')->where($map)->count();
            $this->result(0,'成功',$data,$total);
        }
        return $this->fetch();
    }
    public function creditadd(){
        $id=input('param.id');
        $map=['id'=>$id,'acid'=>1];
        if(isset($id)){
            $item=Db::name('credit')
                ->where($map)
                ->find();
            if(!empty($item)){
                $this->assign('item',$item);
            }
        }else{
            $this->assign('item',array());
        }
        if(request()->isPost()){
            $data = input('post.');
            $v=$data['status'];
            if($v=='on'){
                $data['status']=1;
            }elseif ($v=='off'){
                $data['status']=0;
            }
            if($data['id']!=''){
                $res=Db::name('credit')->where('id',$data['id'])->update($data);
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
                $res=Db::name('credit')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }
        return $this->fetch();
    }
}
