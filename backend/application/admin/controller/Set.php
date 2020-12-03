<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use think\Db;
use org\Qiniu;

class Set extends Base
{

    public function message(){
        $item=Db::name('set')
            ->where('acid',1)
            ->find();
        $this->assign('item',$item);
        if(request()->isPost()){
            $data = input('post.');
            if(!empty($item)){
                $res=Db::name('set')->where('acid',1)->update($data);
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
                $res=Db::name('set')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }
        return $this->fetch();
    }
    public function tplmsg(){
        $item=Db::name('set')
            ->where('acid',1)
            ->find();
        $this->assign('item',$item);
        if(request()->isPost()){
            $data = input('post.');
            if(!empty($item)){
                $res=Db::name('set')->where('acid',1)->update($data);
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
                $res=Db::name('set')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }
        return $this->fetch();
    }

    public function h5(){
        $item=Db::name('set_h5')
            ->where('acid',1)
            ->find();
        $this->assign('item',$item);
        if(request()->isPost()){
            $data = input('post.');
            if(!empty($item)){
                $res=Db::name('set_h5')->where('acid',1)->update($data);
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
                $res=Db::name('set_h5')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }
        return $this->fetch();
    }
    public function smallapp(){
        $item=Db::name('set_smallapp')
            ->where('acid',1)
            ->find();
        $this->assign('item',$item);
        if(request()->isPost()){
            $data = input('post.');
            if(!empty($item)){
                $res=Db::name('set_smallapp')->where('acid',1)->update($data);
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
                $res=Db::name('set_smallapp')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }
        return $this->fetch();
    }
    public function apps(){
        $item=Db::name('set_app')
            ->where('acid',1)
            ->find();
        $this->assign('item',$item);
        if(request()->isPost()){
            $data = input('post.');
            if(!empty($item)){
                $res=Db::name('set_app')->where('acid',1)->update($data);
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
                $res=Db::name('set_app')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }
        return $this->fetch();
    }
    public function basic(){
        $item=Db::name('set')
            ->where('acid',1)
            ->find();
        $this->assign('item',$item);
        if(request()->isPost()){
            $data = input('post.');
            unset($data['file']);
            if(!empty($item)){
                $res=Db::name('set')->where('acid',1)->update($data);
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
                $res=Db::name('set')->insert($data);
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
