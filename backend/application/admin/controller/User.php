<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use think\Db;
use think\Session;

class User extends Base
{

    /**
     * [index 用户列表]
     * @return [type] [description]
     * @author
     */
    public function index(){
        if(request()->isAjax ()){
            extract(input());
            $map = [];
            if(isset($role)&&$role != ""){
                $map['ag.id'] = $role;
            }
            if(isset($key)&&$key!="")
            {
                $map['a.username|a.real_name'] = ['like',"%" . $key . "%"];
            }
            if(isset($start)&&$start!=""&&isset($end)&&$end=="")
            {
                $map['a.last_login_time'] = ['>= time',$start];
            }
            if(isset($end)&&$end!=""&&isset($start)&&$start=="")
            {
                $map['a.last_login_time'] = ['<= time',$end];
            }
            if(isset($start)&&$start!=""&&isset($end)&&$end!="")
            {
                $map['a.last_login_time'] = ['between time',[$start,$end]];
            }
            $Nowpage = input('page') ? input('page'):1;
            $limits = input("limit")?input("limit"):10;// 获取总条数;
            $field=input('field');//字段
            $order=input('order');//排序方式
            if($field && $order){
                $od="a.".$field." ".$order;
            }else{
                $od="a.create_time desc";
            }
            $user = new UserModel();
            $count = $user->getUserCount($map);
            $lists = $user->getUsersByWhere($map,$od, $Nowpage, $limits);
            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }
        $role = Db::name('auth_group')->field('id,title')->order('create_time desc')->select();
        $this->assign ('role',$role);
        return $this->fetch("user/index");
    }


    /**
     * [userAdd 添加用户]
     * @return [type] [description]
     * @author
     */
    public function userAdd()
    {
        if(request()->isPost()){
            $param = input('post.');
            $user = new UserModel();
            $param['password'] = md5(md5($param['password']) . config('auth_key'));
            $base64url = $param['portrait'];
            $res = base64_img($base64url,true);
            if($res['code'] == 200){
                $param['portrait'] = $res['msg'];
            }elseif($res['code'] == 100){
                writelog('添加管理员【'.$param['username'].'】上传头像失败',100);
                return json($res);
            }
            $flag = $user->insertUser($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $role = new UserType();
        $this->assign('role',$role->getRole());
        return $this->fetch('user/useradd');
    }

    /**
     * checkName 验证管理员名称唯一性
     */
    public function checkName(){
        extract(input());
        if(isset($id)&&$id!=""){
            $uid = $id;
        }else{
            $uid = '';
        }
        $user = new UserModel();
        $flag =  $user->checkName ($username,$uid);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * [userEdit 编辑用户]
     * @return [type] [description]
     * @author
     */
    public function userEdit()
    {
        $user = new UserModel();
        if(request()->isPost()){
            $param = input('post.');
            if(empty($param['password'])){
                unset($param['password']);
            }else{
                $param['password'] = md5(md5($param['password']) . config('auth_key'));
            }
            $base64url = $param['portrait'];
            $res = base64_img($base64url,true);
            $have = "";
            if($res['code'] == 200){
                $param['portrait'] = $res['msg'];
                //判断编辑的是不是自己的头像
                if(session('uid')==$param['id']){
                    $have = "have";
                }
            }elseif($res['code'] == 100){
                writelog('编辑管理员【'.$param['username'].'】上传头像失败',100);
                return json($res);
            }
            $flag = $user->editUser($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg'],'type'=>$have]);
        }

        $id = input('param.id');
        if($id != "1"){
            $role = new UserType();
            $this->assign([
                'user' => $user->getOneUser($id),
                'role' => $role->getRole()
            ]);
            //普通管理员编辑页面
            return $this->fetch("user/useredit");
        }else{
            $this->assign([
                'user' => $user->getOneUser($id)
            ]);
            //超级管理员编辑页面
            return $this->fetch("user/editadmin");
        }

    }

    /**
     * [adminEdit 编辑超级管理员]
     * @return [type] [description]
     * @author
     */
    public function adminEdit(){
        $user = new UserModel();
        $oldpassword = md5(md5(input('oldpassword')).config('auth_key'));
        if(input('type')=="checkPassword"){
            $flag =  $user->checkOldPassword ($oldpassword,session('uid'));
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }else{
            $param = input('post.');
            if(empty($param['password'])){
                unset($param['password']);
            }else{
                $param['password'] = md5(md5($param['password']) . config('auth_key'));
            }
            $base64url = $param['portrait'];
            $res = base64_img($base64url,true);
            $have = "";
            if($res['code'] == 200){
                $param['portrait'] = $res['msg'];
                //判断编辑的是不是自己的头像
                if(session('uid')==$param['id']){
                    $have = "have";
                }
            }elseif($res['code'] == 100){
                writelog('编辑管理员【'.$param['username'].'】上传头像失败',100);
                return json($res);
            }
            $flag = $user->editUser($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg'],'type'=>$have]);
        }
    }

    /**
     * [UserDel 删除用户]
     * @return [type] [description]
     * @author
     */
    public function UserDel()
    {
        $id = input('param.id');
        if(session('uid')==$id){
            return json(['code'=>100,'msg'=>'不能删除自己']);
        }else{
            $role = new UserModel();
            $flag = $role->delUser($id);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
    

    /**
     * [user_state 用户状态]
     * @return [type] [description]
     * @author
     */
    public function user_state()
    {
        extract(input());
        $role = new UserModel();
        $flag = $role->userState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }


    /**
     * editPwd 修改管理员密码
     * @return \think\response\Json
     */
    public function editPwd(){
        extract(input());
        $user = new UserModel();
        if(isset($type) && $type=='checkPassword'){
            $old_pwd = md5(md5($old_pwd).config('auth_key'));
            $flag =  $user->checkOldPassword ($old_pwd,session('uid'));
            return json(['code'=>$flag['code'],'msg'=>$flag['msg']]);
        }else{
            $param['password'] = md5(md5($new_pwd).config('auth_key'));
            $flag = $user->editPassword($param);
            return json(['code'=>$flag['code'],'msg'=>$flag['msg']]);
        }
    }

    /**
     * batchDelUser 批量删除管理员
     * @return \think\response\Json
     */
    public function batchDelUser(){
        extract(input());
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的记录！']);
        }
        $ids = explode(',',$ids);
        if(in_array('1',$ids)){
            $key = array_search ('1',$ids);
            unset($ids[$key]);
            if(empty($ids)){
                return json(['code'=>100,'msg'=>'不可删除超级管理员']);
                die;
            }
        }
        if(in_array(session('uid'),$ids)){
            $key = array_search (session('uid'),$ids);
            unset($ids[$key]);
            if(empty($ids)){
                return json(['code'=>100,'msg'=>'不可删除自己']);
                die;
            }
        }
        $ids = array_merge($ids);
        $user = new UserModel();
        $flag = $user->batchDelUser($ids);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }


    /**
     * usingAdmin 批量启用管理员
     * @return \think\response\Json
     */
    public function usingAdmin(){
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
        $user = new UserModel();
        $flag = $user->usingAdmin($list);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * forbiddenAdmin 批量禁用管理员
     * @return \think\response\Json
     */
    public function forbiddenAdmin(){
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
            if(in_array(session('uid'),$ids)){
                $key = array_search (session('uid'),$ids);
                unset($ids[$key]);
                if(empty($ids)){
                    return json(['code'=>100,'msg'=>'不可禁用自己']);
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
        $user = new UserModel();
        $flag = $user->forbiddenAdmin($list);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }

    /**
     * 导出Excel
     * @return \think\response\Json
     */
    public function excelAdmin(){
        extract(input());
        if($ids =="" && $key == "" && $start == "" && $end == "" && $role ==""){
            $data = Db::name('admin')->select();
        }
        if($ids != ""){
            $ids = trim($ids,',');
            $ids = explode(',',$ids);
            $data = Db::name('admin')->where('id','in',$ids)->select();
        }else{
            $map = [];
            if($role != ""){
                $map['ag.id'] = $role;
            }
            if($key!="")
            {
                $map['a.username|a.real_name'] = ['like',"%" . $key . "%"];
            }
            if($start!=""&&$end=="")
            {
                $map['a.last_login_time'] = ['>= time',$start];
            }
            if($end!=""&&$start=="")
            {
                $map['a.last_login_time'] = ['<= time',$end];
            }
            if($start!=""&&$end!="")
            {
                $map['a.last_login_time'] = ['between time',[$start,$end]];
            }
            $data = Db::name('admin')
                ->alias ('a')
                ->join('auth_group ag', 'a.groupid = ag.id','left')
                ->field('a.id,username,a.password,a.portrait,a.loginnum,a.last_login_ip,a.last_login_time,a.real_name,phone,a.status,a.groupid,a.create_time,a.update_time')
                ->where($map)
                ->select();
        }
        $cellname = [
            ['id','ID',15,'LEFT'],
            ['username','昵称',15,'LEFT'],
            ['password','密码',15,'LEFT'],
            ['portrait','头像',20,'LEFT'],
            ['loginnum','登录次数',15,'LEFT'],
            ['last_login_ip','上次登录ip',15,'LEFT'],
            ['last_login_time','上次登录时间',15,'LEFT'],
            ['real_name','真实姓名',15,'LEFT'],
            ['phone','手机号',15,'LEFT'],
            ['status','状态',15,'LEFT'],
            ['groupid','角色id',15,'LEFT'],
            ['create_time','创建时间',15,'LEFT'],
            ['update_time','修改时间',15,'LEFT']
        ];
        $res = exportExcel('管理员信息','admin',$cellname,$data);
        return json($res);
    }

}