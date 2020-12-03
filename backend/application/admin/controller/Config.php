<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use app\admin\model\ConfigModel;
use think\Db;

class Config extends Base
{

    /**
     * [index 配置列表]
     */
    public function index(){
        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($type)&&$type!=""){
                $map['type'] = $type;
            }
            if(isset($group)&&$group!=""){
                $map['group'] = $group;
            }
            if(isset($key)&&$key!="")
            {
                $map['title|name'] = ['like',"%" . $key . "%"];
            }
            if(isset($start)&&$start!=""&&isset($end)&&$end=="")
            {
                $map['create_time'] = ['>= time',$start];
            }
            if(isset($end)&&$end!=""&&isset($start)&&$start=="")
            {
                $map['create_time'] = ['<= time',$end];
            }
            if(isset($start)&&$start!=""&&isset($end)&&$end!="")
            {
                $map['create_time'] = ['between time',[$start,$end]];
            }
            $field=input('field');//字段
            $order=input('order');//排序方式
            if($field && $order){
                $od=$field." ".$order;
            }else{
                $od="create_time desc";
            }
            $config = new ConfigModel();
            $Nowpage = input('page') ? input('page'):1;
            $limits = input("limit")?input("limit"):10;
            $lists = $config->getAllConfig($map, $Nowpage, $limits,$od);
            $count = $config->getAllCount($map);//计算总页面
            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }
        $this->assign ('type',config('config_type_list'));
        $this->assign ('group',config('config_group_list'));
        return $this->fetch("config/index");
    }


    /**
     * [add_config 添加配置]
     */
    public function add_config()
    {
        if(request()->isPost()){
            extract(input());
            $param = input('post.');
            if(!isset($status)){
                $param['status'] = 2;
            }
            $config = new ConfigModel();
            $flag = $config->insertConfig($param);
            cache('db_config_data',null);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }

    /**
     * 验证配置标识的唯一性
     */
    public function checkConfig(){
        extract(input());
        $user = new ConfigModel();
        if(isset($id)&&$id!=""){
            $uid = $id;
        }else{
            $uid = '';
        }
        $flag = $user->checkConfig($name,$uid);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * [edit_config 编辑配置]
     */
    public function edit_config()
    {
        $config = new ConfigModel();
        if(request()->isPost()){
            $param = input('post.');
            $flag = $config->editConfig($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $info = $config->getOneConfig($id);
        $this->assign('info', $info);
//        dump($info);die;
        return $this->fetch();
    }


    /**
     * [del_config 删除配置]
     * @author
     */
    public function del_config()
    {
        $id = input('param.id');
        $config = new ConfigModel();
        $flag = $config->delConfig($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }



    /**
     * [user_state 配置状态]
     * @author
     */
    public function status_config()
    {
        extract(input());
        $config = new ConfigModel();
        $flag = $config->configState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }


    /**
     * [获取某个标签的配置参数]
     * @author
     */
    public function group() {
        $id   = input('id',1);
        $type = config('config_group_list'); 
        $list = Db::name("Config")->where(array('status'=>1,'group'=>$id))->field('id,name,title,extra,value,remark,type')->order('sort')->select();
        $this->assign('list',$list);
        $this->assign('id',$id);
        return $this->fetch();
    }



    /**
     * [批量保存配置]
     * @author
     */
    public function save($config){
        $id = input('param.id');
        $conf = new ConfigModel();
        $flag = $conf->configSave($config);
        if($flag['code']==200){
            cache('db_config_data',null);
        }
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * batchDelConfig 批量删除配置
     * @return \think\response\Json
     */
    public function batchDelConfig(){
        extract(input());
        $config = new ConfigModel();
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的记录！']);
        }
        $flag = $config->batchDelConfig($ids);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * usingConfig 批量启用
     * @return \think\response\Json
     */
    public function usingConfig(){
        extract(input());
        $list = [];
        if($ids){
            $ids = explode(',',$ids);
            for($i=0;$i<count($ids);$i++){
                $param = [
                    'id'=>$ids[$i],
                    'status'=>1
                ];
                $list[] = $param;
            }
        }
        $conf = new ConfigModel();
        $flag = $conf->usingConfig($list);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * 批量禁用配置
     * @return \think\response\Json
     */
    public function forbiddenConfig(){
        extract(input());
        $list = [];
        if($ids){
            $ids = explode(',',$ids);
            for($i=0;$i<count($ids);$i++){
                $param = [
                    'id'=>$ids[$i],
                    'status'=>2
                ];
                $list[] = $param;
            }
        }
        $conf = new ConfigModel();
        $flag = $conf->forbiddenConfig($list);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }
}