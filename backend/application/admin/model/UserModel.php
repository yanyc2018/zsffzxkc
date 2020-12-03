<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\model;
use think\Model;
use think\Db;

class UserModel extends Model
{
    protected $name = 'admin';
    protected $autoWriteTimestamp = true;   // 开启自动写入时间戳
    /**
     * 根据搜索条件获取用户列表信息
     */
    public function getUsersByWhere($map,$od, $Nowpage, $limits)
    {
        return $this->alias ('a')
            ->join('auth_group ag', 'a.groupid = ag.id','left')
            ->field('a.id,a.username,a.password,a.portrait,a.loginnum,a.last_login_ip,a.last_login_time,a.real_name,a.status,a.phone,a.groupid,a.create_time,a.update_time,ag.title')
            ->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();
    }

    /*
     * 总页面数
     */
    public function getUserCount($map){
       return $this->alias('a')
            ->join('auth_group ag', 'a.groupid = ag.id','left')
            ->where($map)
            ->count();
    }

    /**
     * 根据搜索条件获取所有的用户数量
     * @param $where
     */
    public function getAllUsers($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入管理员信息
     * @param $param
     */
    public function insertUser($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param);
            Db::name('auth_group_access')->insert(['uid'=> $this->id,'group_id'=> $param['groupid']]);
            Db::commit();// 提交事务
            writelog('管理员【'.$param['username'].'】添加成功',200);
            return ['code' => 200, 'data' =>"", 'msg' => '添加管理员成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('管理员【'.$param['username'].'】添加失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '添加管理员失败'];
        }
    }

    /**
     * 编辑管理员信息
     * @param $param
     */
    public function editUser($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param, ['id' => $param['id']]);
            if($param['id'] != 1){
                Db::name('auth_group_access')->where('uid',$param['id'])->setField ('group_id',$param['groupid']);
            }
            Db::commit();// 提交事务
            writelog('管理员【'.$param['username'].'】编辑成功',200);
            $status = '';
            if($param['id']==session('uid')){
                session('portrait', $param['portrait']); //用户头像
                if(isset($param['password']) && $param['password'] != ""){
                    $status = 100;
                }
            }
            return ['code' => 200, 'data' => $status, 'msg' => '编辑用户成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('管理员【'.$param['username'].'】编辑失败',100);
            return ['code' => 100, 'data' => '', 'msg' =>'编辑用户失败'];
        }
    }


    /**
     * 验证原始密码
     * @param $param
     */
    public function checkOldPassword($oldpassword,$id){
        $password =  $this->where("id",$id)->value("password");
        if($password === $oldpassword){
            return ['code' => 200, 'data' => '', 'msg' =>'true'];
        }else{
            return ['code' => 100, 'data' => '', 'msg' =>'false'];
        }

    }

    /**
     * checkName 验证管理员名称唯一性
     * @param $username
     * @return string
     */
    public function checkName($username,$uid){
        if($uid != ''){
            $uname = $this->where('id',$uid)->value('username');
            if($uname == $username){
                return ['code' => 200, 'msg' => 'true'];
            }
        }
        $result = $this->where('username',$username)->find();
        if($result){
            return ['code' => 100, 'msg' => 'false'];
        }else{
            return ['code' => 200, 'msg' => 'true'];
        }
    }


    /**
     * 根据管理员id获取角色信息
     * @param $id
     */
    public function getOneUser($id)
    {
        return $this->where('id', $id)->find();
    }


    /**
     * 删除管理员
     * @param $id
     */
    public function delUser($id)
    {
        $name = $this->where('id', $id)->value('username');
        Db::startTrans();// 启动事务
        try{
            $this->where('id', $id)->delete();
            Db::name('auth_group_access')->where('uid', $id)->delete();
            Db::commit();// 提交事务
            writelog('管理员【'.$name.'】删除成功(ID='.$id.')',200);
            return ['code' => 200, 'data' => '', 'msg' => '删除用户成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('管理员【'.$name.'】删除失败(ID='.$id.')',100);
            return ['code' => 100, 'data' => '', 'msg' => '删除用户失败'];
        }
    }


    /**
     * editPassword 修改管理员密码
     * @param $param
     * @return array
     */
    public function editPassword($param){
        $name = $this->where('id',session('uid'))->value('username');
        Db::startTrans();// 启动事务
        try{
            $this->allowField (true)->save($param,['id'=>session('uid')]);
            Db::commit();// 提交事务
            writelog('管理员【'.$name.'】修改密码成功',200);
            return ['code'=>200,'msg'=>'密码修改成功，请重新登录！'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('管理员【'.$name.'】修改密码失败',100);
            return ['code'=>100,'msg'=>'密码修改失败'];
        }
    }

    /**
     * batchDelUser 批量删除管理员
     * @param $param
     * @return array
     */
    public function batchDelUser($param){
        Db::startTrans();// 启动事务
        try{
            UserModel::destroy($param);
            for($i=0;$i<count($param);$i++){
                Db::name('auth_group_access')->where('uid','in',$param)->delete();
            }
            Db::commit();// 提交事务
            writelog('批量删除管理员成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '批量删除成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('批量删除管理员失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '批量删除失败'];
        }
    }

    /**
     * forbiddenAdmin 批量禁用管理员
     * @param $param
     * @return array
     */
    public function forbiddenAdmin($param){
        Db::startTrans();// 启动事务
        try{
            if($param){
                $this->saveAll($param);
            }else{
                $this->where('id','not in',[1,session('uid')])->update(['status'=>2]);
            }
            Db::commit();// 提交事务
            writelog('批量禁用管理员成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '批量禁用成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('批量禁用管理员失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '批量禁用失败'];
        }
    }

    /**
     * usingAdmin 批量启用管理员
     * @param $param
     * @return array
     */
    public function usingAdmin($param){
        Db::startTrans();// 启动事务
        try{
            if($param){
                $this->saveAll($param);
            }else{
                $this->where('1=1')->update(['status'=>1]);
            }
            Db::commit();// 提交事务
            writelog('批量启用管理员成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '批量启用成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('批量启用管理员失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '批量启用失败'];
        }
    }

    /**
     * [userState 用户状态]
     * @param $id
     * @return array
     */
    public function userState($id,$num){
        $username = $this->where('id',$id)->value('username');
        if($num == 2){
            $msg = '禁用';
        }else{
            $msg = '启用';
        }
        Db::startTrans();// 启动事务
        try{
            if($id == session('uid')){
                return ['code'=>100,'data' => '','msg'=>'不可禁用自己','type'=>'no'];
            }else {
                $this->where ('id' , $id)->setField (['status' => $num]);
                Db::commit();// 提交事务
                writelog('管理员【'.$username.'】'.$msg.'成功',200);
//                    return ['code' => 200, 'data' => '', 'msg' => '已'.$msg];
            }
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('管理员【'.$username.'】'.$msg.'失败',100);
            return ['code' => 100, 'data' => '', 'msg' => $msg.'失败'];
        }
    }

}