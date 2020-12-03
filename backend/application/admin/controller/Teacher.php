<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use think\Db;
use org\Qiniu;
//讲师管理
class Teacher extends Base
{
    public function index(){
        if(request()->isAjax()) {
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = Db::name('teacher')
                ->where(['acid'=>1,'is_verify'=>1])
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            $total=Db::name('teacher')->where(['acid'=>1,'is_verify'=>1])->count();
            $this->result(0,'成功',$data,$total);
        }
        return $this->fetch();
    }
    public function dsh(){
        if(request()->isAjax()) {
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = Db::name('teacher')
                ->where(['acid'=>1,'is_verify'=>0])
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            $total=Db::name('teacher')->where(['acid'=>1,'is_verify'=>0])->count();
            $this->result(0,'成功',$data,$total);
        }
        return $this->fetch();
    }
    public function sh_teacher(){
        if(request()->isAjax()) {
            $id = input('param.id');
            $res=Db::name('teacher')->where('id',$id)->setField('is_verify',1);
            if($res){
                $this->result(0,'审核成功');
            }else{
                $this->result(1,'审核失败');
            }
        }
    }
    public function teacher_list_json(){
        $teachers=Db::name('teacher')
            ->where('acid',1)
            ->select();
        foreach ($teachers as $k=>$v){
            $tlist[$k]['value']=$v['id'];
            $tlist[$k]['label']=$v['imgname'];
        }
        $this->result(0,'成功',$tlist,0);
    }
    public function add_teacher(){
        $id=input('param.id');
        if(isset($id)){
            $item=Db::name('teacher')
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
                $res=Db::name('teacher')->where('id',$data['id'])->update($data);
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
                $res=Db::name('teacher')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }

        return $this->fetch();
    }
    public function teacher_del(){
        if(request()->isAjax()) {
            $id = input('param.id');
            $res=Db::name('teacher')->where('id',$id)->delete();
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
        $res=Db::name('teacher')->where('id',$id)->update($data);
        if($res){
            $this->result(200,'操作成功');
        }else{
            $this->result(500,'操作失败');
        }
    }
}
