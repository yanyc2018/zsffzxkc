<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\model;
use think\Model;
use think\Db;

class UserType extends Model
{
    protected  $name = 'auth_group';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    /**
     * [getRoleByWhere 根据条件获取角色列表信息]
     * @author
     */
    public function getRoleByWhere($map, $Nowpage, $limits,$od)
    {
        return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
    }


    /**
     * [getRoleByWhere 根据条件获取所有的角色数量]
     * @author
     */
    public function getAllRole($where)
    {
        return $this->where($where)->count();
    }

    /**
     * checkName 验证角色名称唯一性
     * @param $username
     * @return string
     */
    public function checkRole($title,$uid){
        if($uid != ''){
            $uname = $this->where('id',$uid)->value('title');
            if($uname == $title){
                return ['code' => 200, 'msg' => 'true'];
            }
        }
        $result = $this->where('title',$title)->find();
        if($result){
            return ['code' => 100, 'msg' => 'false'];
        }else{
            return ['code' => 200, 'msg' => 'true'];
        }
    }

    /**
     * [insertRole 插入角色信息]
     * @author
     */    
    public function insertRole($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->save($param);
            Db::commit();// 提交事务
            writelog('角色【'.$param['title'].'】添加成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '添加角色成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('角色【'.$param['title'].'】添加失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '添加角色失败'];
        }
    }



    /**
     * [editRole 编辑角色信息]
     * @author
     */  
    public function editRole($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->save($param, ['id' => $param['id']]);
            Db::commit();// 提交事务
            writelog('角色【'.$param['title'].'】编辑成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '编辑角色成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('角色【'.$param['title'].'】编辑失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '编辑角色失败'];
        }
    }



    /**
     * [getOneRole 根据角色id获取角色信息]
     * @author
     */ 
    public function getOneRole($id)
    {
        return $this->where('id', $id)->find();
    }



    /**
     * [delRole 删除角色]
     * @author
     */ 
    public function delRole($id)
    {
        $title = $this->where('id',$id)->value('title');
        Db::startTrans();// 启动事务
        try{
            $this->where('id', $id)->delete();
            Db::commit();// 提交事务
            writelog('角色【'.$title.'】删除成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '删除角色成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('角色【'.$title.'】删除失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '删除角色失败'];
        }
    }



    /**
     * [getRole 获取所有的角色信息]
     * @author
     */ 
    public function getRole()
    {
        return $this->where('id','<>',1)->select();
    }


    /**
     * [getRole 获取角色的权限节点]
     * @author
     */ 
    public function getRuleById($id)
    {
        $res = $this->field('rules')->where('id', $id)->find();
        return $res['rules'];
    }


    /**
     * [editAccess 分配权限]
     * @author
     */ 
    public function editAccess($param)
    {
        $title = $this->where('id',$param['id'])->value('title');
        Db::startTrans();// 启动事务
        try{
            $this->save($param, ['id' => $param['id']]);
            Db::commit();// 提交事务
            writelog('角色【'.$title.'】分配权限成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '分配权限成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('角色【'.$title.'】分配权限失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '分配权限失败'];
        }
    }



    /**
     * [getRoleInfo 获取角色信息]
     * @author
     */ 
    public function getRoleInfo($id){

        $result = Db::name('auth_group')->where('id', $id)->find();
        if($result['rules'] == "SUPERAUTH"){
            $res = Db::name('auth_rule')->field('name')->select();

            foreach($res as $key=>$vo){
                if('#' != $vo['name']){
                    $result['name'][] = $vo['name'];
                }
            }
        }elseif(empty($result['rules'])){
            $result['title'] ="";
            $result['rules'] ="";
            $result['name'] ="";
        }else{
            $where = 'id in('.$result['rules'].')';
            $res = Db::name('auth_rule')->field('name')->where($where)->select();

            foreach($res as $key=>$vo){
                if('#' != $vo['name']){
                    $result['name'][] = $vo['name'];
                }
            }
        }
        return $result;
    }

    /**
     * batchDelRole 批量删除角色
     * @param $param
     * @return array
     */
    public function batchDelRole($param){
        Db::startTrans();// 启动事务
        try{
            UserType::destroy($param);
            Db::commit();// 提交事务
            writelog('角色批量删除成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '批量删除成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('角色批量删除成功',100);
            return ['code' => 100, 'data' => '', 'msg' => '批量删除失败'];
        }
    }

    /**
     * [roleState 角色状态]
     * @param $param
     * @return array
     */
    public function roleState($id,$num){
        $title = $this->where('id',$id)->value('title');
        if($num == 2){
            $msg = '禁用';
        }else{
            $msg = '启用';
        }
        Db::startTrans();// 启动事务
        try{
            if($id == session('agid')){
                return ['code'=>100,'data' => '','msg'=>'不可禁用自己的角色','type'=>'no'];
            }else {
                $this->where ('id' , $id)->setField (['status' => $num]);
                Db::commit();// 提交事务
                writelog('角色【'.$title.'】'.$msg.'成功',200);
//                    return ['code' => 200 , 'data' => '' , 'msg' => '已'.$msg];
            }
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('角色【'.$title.'】'.$msg.'失败',100);
            return ['code' => 100, 'data' => '', 'msg' => $msg.'失败'];
        }
    }

    /**
     * forbiddenRole 批量禁用角色
     * @param $param
     * @return array
     */
    public function forbiddenRole($param){
        Db::startTrans();// 启动事务
        try{
            if($param){
                $this->saveAll($param);
            }else{
                $this->where('id','not in',[1,session('agid')])->update(['status'=>2]);
            }
            Db::commit();// 提交事务
            writelog('批量禁用角色成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '批量禁用成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('批量禁用角色失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '批量禁用失败'];
        }
    }

    /**
     * usingRole 批量启用角色
     * @param $param
     * @return array
     */
    public function usingRole($param){
        Db::startTrans();// 启动事务
        try{
            if($param){
                $this->saveAll($param);
            }else{
                $this->where('1=1')->update(['status'=>1]);
            }
            Db::commit();// 提交事务
            writelog('批量启用角色成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '批量启用成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('批量启用角色失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '批量启用失败'];
        }
    }

}