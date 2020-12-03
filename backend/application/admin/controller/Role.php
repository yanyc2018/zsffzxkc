<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use app\admin\model\Node;
use app\admin\model\UserType;
use think\Db;

class Role extends Base
{

    /**
     * [index 角色列表]
     */
    public function index(){
        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($key)&&$key!="")
            {
                $map['title'] = ['like',"%" . $key . "%"];
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
            $user = new UserType();
            $Nowpage = input('page') ? input('page'):1;
            $limits = input("limit")?input("limit"):10;// 获取总条数;
            $count = $user->getAllRole($map);  //总数据
            $lists = $user->getRoleByWhere($map, $Nowpage, $limits,$od);
            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }
        return $this->fetch("role/index");
    }



    /**
     * [roleAdd 添加角色]
     */
    public function roleAdd()
    {
        if(request()->isPost()){
            extract(input());
            $param = input('post.');
            $role = new UserType();
            $flag = $role->insertRole($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        return $this->fetch('role/roleadd');
    }

    /**
     * checkRole 验证角色名称唯一性
     */
    public function checkRole(){
        extract(input());
        if(isset($id)&&$id!=""){
            $uid = $id;
        }else{
            $uid = '';
        }
        $role = new UserType();
        $flag = $role->checkRole ($title,$uid);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * [roleEdit 编辑角色]
     */
    public function roleEdit()
    {
        $role = new UserType();
        if(request()->isPost()){
            $param = input('post.');
            $flag = $role->editRole($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id');
        $this->assign([
            'role' => $role->getOneRole($id)
        ]);
        return $this->fetch('role/roleedit');
    }



    /**
     * [roleDel 删除角色]
     * @return [type] [description]
     * @author
     */
    public function roleDel()
    {
        $id = input('param.id');
        if($id == session('agid')){
            return json(['code'=>100,'msg'=>'不能删除自己的角色']);
        }else{
            $role = new UserType();
            $flag = $role->delRole($id);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * [role_state 用户状态]
     * @return [type] [description]
     * @author
     */
    public function role_state()
    {
        extract(input());
        $role = new UserType();
        $flag = $role->roleState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }



    /**
     * [giveAccess 分配权限]
     * @return [type] [description]
     * @author
     */
    public function giveAccess()
    {
        $param = input('param.');
        $node = new Node();
        //获取现在的权限
        if('get' == $param['type']){
            $nodeStr = $node->getNodeInfo($param['id']);
            return json(['code' => 200, 'data' => $nodeStr, 'msg' => 'success']);
        }
        //分配新权限
        if('give' == $param['type']){

            $doparam = [
                'id' => $param['id'],
                'rules' => $param['rule']
            ];
            $user = new UserType();
            $flag = $user->editAccess($doparam);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * batchDelRole 批量删除角色
     * @return \think\response\Json
     */
    public function batchDelRole(){
        extract(input());
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的记录！']);
        }
        $ids = explode(',',$ids);
        if(in_array('1',$ids)){
            $key = array_search ('1',$ids);
            unset($ids[$key]);
            if(empty($ids)){
                return json(['code'=>100,'msg'=>'不可删除超级管理员角色']);
                die;
            }
        }
        if(in_array(session('agid'),$ids)){
            $key = array_search (session('agid'),$ids);
            unset($ids[$key]);
            if(empty($ids)){
                return json(['code'=>100,'msg'=>'不可删除自己的角色']);
                die;
            }
        }
        $ids = array_merge($ids);
        $user = new UserType();
        $flag = $user->batchDelRole($ids);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * usingAll 批量启用
     * @return \think\response\Json
     */
    public function usingRole(){
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
        $user = new UserType();
        $flag = $user->usingRole($list);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * batchForbidden 批量禁用
     * @return \think\response\Json
     */
    public function forbiddenRole(){
        extract(input());
        $list = [];
        if($ids){
            $ids = explode(',',$ids);
            if(in_array('1',$ids)){
                $key = array_search ('1',$ids);
                unset($ids[$key]);
                if(empty($ids)){
                    return json(['code'=>100,'msg'=>'不可禁用超级管理员']);
                    die;
                }
            }
            if(in_array(session('agid'),$ids)){
                $key = array_search (session('agid'),$ids);
                unset($ids[$key]);
                if(empty($ids)){
                    return json(['code'=>100,'msg'=>'不可禁用自己的角色']);
                    die;
                }
            }
            $ids = array_merge($ids);
            for($i=0;$i<count($ids);$i++){
                $param = [
                    'id'=>$ids[$i],
                    'status'=>2
                ];
                $list[] = $param;
            }
        }
        $user = new UserType();
        $flag = $user->forbiddenRole($list);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

}