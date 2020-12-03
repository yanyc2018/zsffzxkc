<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use think\Db;
use org\Qiniu;
//课程管理
class Course extends Base
{
    public function status_switch(){
        $id = input('param.id');
        $type=input('param.type');
        if($type=='menu'){
            $table=Db::name('coursemenu');
        }else{
            $table=Db::name('course');
        }
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
    public function tj_switch(){
        $id = input('param.id');
        $type=input('param.type');
        if($type=='menu'){
            $table=Db::name('coursemenu');
        }else{
            $table=Db::name('course');
        }
        $status = input('param.is_tj');
        $data=array(
            'is_tj'=>$status
        );
        $res=$table->where('id',$id)->update($data);
        if($res){
            $this->result(200,'操作成功');
        }else{
            $this->result(500,'操作失败');
        }
    }
    public function index(){
        $media=input('param.media');
        $this->assign('media',$media);
        if(request()->isAjax()) {
            $map=array(
                'a.acid'=>1
            );
            $key=input('keyword');

            if(isset($key)&&$key!=""){
                $map['a.menuname'] = ['like',"%" . $key . "%"];
            }
            if(isset($media)&&$media!=""){
                $map['a.media'] = $media;
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;
            $order=input('order');//排序方式
            $data = Db::name('coursemenu')
                ->alias('a')
                ->join('teacher b','a.tid=b.id')
                ->field(['a.*','b.imgname'=>'teacher'])
                ->where('a.status','in','1,0')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            foreach ($data as $k=>$v){
                if($v['media']=='video'){
                    $media='视频';
                }elseif($v['media']=='audio'){
                    $media='音频';
                }elseif($v['media']=='tuwen'){
                    $media='图文';
                }elseif($v['media']=='all'){
                    $media='不限';
                }
                $classifyname='';
                if(strpos($v['fenleiid'],',')){
                    $fls=explode(",",$v['fenleiid']);
                    foreach ($fls as $key=>$val){
                        $title=Db::name('classify')
                            ->field('title')
                            ->where('id',$val)
                            ->find();
                        $classifyname.=($title['title'].' | ');
                    }
                    $classifyname=rtrim($classifyname,' |');
                }else{
                    $title=Db::name('classify')
                        ->field('title')
                        ->where('id',$v['fenleiid'])
                        ->find();
                    $classifyname=$title['title'];
                }
                $data[$k]['media_name']=$media;
                $data[$k]['classify']=$classifyname;
            }
            $total=Db::name('coursemenu')->alias('a')->where($map)->count();
            $this->result(0,'成功',$data,$total);
        }
        return $this->fetch();
    }
    public function course_list(){
        $menuid=input('param.menuid');
        $this->assign('menuid',$menuid);
        if(request()->isAjax()) {
            $map = array(
                'a.acid' => 1
            );
            $key = input('keyword');

            if (isset($key) && $key != "") {
                $map['a.coursename'] = ['like', "%" . $key . "%"];
            }
            if (isset($media) && $media != "") {
                $map['a.media'] = $media;
            }
            $menuid = input('param.menuid');
            $map['menuid']=$menuid;
            $Nowpage = input('get.page') ? input('get.page') : 1;
            $limits = input("limit") ? input("limit") : 10;
            $order = input('order');//排序方式

            $data = Db::name('course')
                ->alias('a')
                ->join('coursemenu b', 'a.menuid=b.id')
                ->field(['a.*', 'b.menuname'])
                ->where('a.status', 'in', '1,0')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($order)
                ->select();
            foreach ($data as $k=>$v) {
                if ($v['media'] == 'video') {
                    $media = '视频';
                } elseif ($v['media'] == 'audio') {
                    $media = '音频';
                } elseif ($v['media'] == 'tuwen') {
                    $media = '图文';
                }
                $data[$k]['media_name']=$media;
            }

            //$total=Db::name('course')->count();
            $this->result(0,'成功',$data);
        }
        return $this->fetch();
    }
    public function menu_del(){
        if(request()->isAjax()) {
            $id = input('param.id');
            $map=['id'=>$id,'acid'=>1];
            $res=Db::name('coursemenu')->where($map)->update(['status'=>2]);
            if($res){
                $this->result(0,'删除成功');
            }else{
                $this->result(1,'删除失败');
            }
        }
    }
    public function course_del(){
        if(request()->isAjax()) {
            $id = input('param.id');
            $map=['id'=>$id,'acid'=>1];
            $res=Db::name('course')->where($map)->update(['status'=>2]);
            if($res){
                $this->result(0,'删除成功');
            }else{
                $this->result(1,'删除失败');
            }
        }
    }
    public function add_menu(){
        $id=input('param.id');
        $map=['id'=>$id,'acid'=>1];
        if(isset($id)){
            $item=Db::name('coursemenu')
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
            unset($data['file']);
            foreach ($data as $k=>$v){
                if($v=='on'){
                    $data[$k]=1;
                }elseif ($v=='off'){
                    $data[$k]=0;
                }
            }
            if($data['id']!=''){
                $res=Db::name('coursemenu')->where('id',$data['id'])->update($data);
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
                $res=Db::name('coursemenu')->insert($data);
                if($res){
                    $this->result('0','添加成功');
                }else{
                    $this->result('1','添加失败');
                }
            }
        }
        return $this->fetch();
    }
    public function add_course(){
        $menuid=input('param.menuid');
        $media=input('param.media');
        $map=['id'=>$menuid,'acid'=>1];
        if(isset($menuid)){
            $menuinfo=Db::name('coursemenu')
                ->where($map)
                ->find();
            if(isset($media)){
                $menuinfo['media']=$media;
            }
            $this->assign('menuinfo',$menuinfo);
        }else{
            $this->assign('menuinfo',array());
        }
        $menus=Db::name('coursemenu')
            ->where('acid',1)
            ->select();
        $medias=array(
            ['medianame'=>'视频','val'=>'video'],
            ['medianame'=>'音频','val'=>'audio'],
            ['medianame'=>'图文','val'=>'tuwen'],
        );
        $this->assign('menus',$menus);
        $this->assign('medias',$medias);
        $id=input('param.id');
        $map1=['id'=>$id,'acid'=>1];
        if(isset($id)){
            $item=Db::name('course')
                ->where($map1)
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
            $menumedia=$data['menumedia'];
            $data1=array(
                'menuid'=>$data['menuid'],
                'acid'=>1,
                'coursename'=>$data['coursename'],
                'freesecond'=>$data['freesecond'],
                'addtime'=>time(),
                'price'=>$data['price'],
                'xh'=>0,
                'is_sk'=>$data['is_sk']
            );
            switch ($menumedia){
                case 'video':
                    $data1['src']=$data['video_src'];
                    $data1['media']=$menumedia;
                    break;
                case 'audio':
                    $data1['src']=$data['audio_src'];
                    $data1['media']=$menumedia;
                    break;
                case 'tuwen':
                    $data1['media']=$menumedia;
                    $data1['introduce']=$data['introduce'];
                    break;
            }
            if($data['id']!=''){
                $res=Db::name('course')->where('id',$data['id'])->update($data1);
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
                $res=Db::name('course')->insert($data1);
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
