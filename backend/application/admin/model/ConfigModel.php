<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\model;
use think\Model;
use think\Db;

class ConfigModel extends Model
{
    protected $name = 'config';

    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 根据条件获取配置列表信息
     */
    public function getAllConfig($map, $nowpage, $limits,$od)
    {
        $data = $this->where($map)->page($nowpage, $limits)->order($od)->select();
        for($i=0;$i<count($data);$i++){
            $data[$i]['type'] =  get_config_type( $data[$i]['type']);
            $data[$i]['group'] =  get_config_group( $data[$i]['group']);
        }
        return $data;
    }

    /**
     * 根据条件获取所有配置信息数量
     * @param $map
     */
    public function getAllCount($map)
    {
        return $this->where($map)->count();
    }

    /**
     * 验证配置标识的唯一性
     */
    public function checkConfig($name,$uid){
        if($uid != ''){
            $uname = $this->where('id',$uid)->value('name');
            if($uname == $name){
                return ['code' => 200, 'msg' => 'true'];
            }
        }
        $result = $this->where('name',$name)->find();
        if($result){
            return ['code' => 100, 'msg' => 'false'];
        }else{
            return ['code' => 200, 'msg' => 'true'];
        }
    }

    /**
     * [insertConfig 添加配置信息]
     * @param $param
     */
    public function insertConfig($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param);
            Db::commit();// 提交事务
            writelog('配置【'.$param['name'].'】添加成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '添加成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('配置【'.$param['name'].'】添加失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '添加失败'];
        }
    }

    /**
     * 编辑信息
     * @param $param
     */
    public function editConfig($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param, ['id' => $param['id']]);
            Db::commit();// 提交事务
            writelog('配置【'.$param['name'].'】编辑成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '编辑成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('配置【'.$param['name'].'】编辑失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '编辑失败'];
        }
    }


    /**
     * 根据id获取配置信息
     * @param $id
     */
    public function getOneConfig($id)
    {
        return $this->where('id', $id)->find();
    }


    /**
     * 删除配置
     * @param $id
     */
    public function delConfig($id)
    {
        $name = $this->where('id',$id)->value('name');
        Db::startTrans();// 启动事务
        try{
            $this->where('id', $id)->delete();
            Db::commit();// 提交事务
            writelog('配置【'.$name.'】删除成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '删除成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('配置【'.$name.'】删除失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '删除失败'];
        }
    }

    /**
     * batchDelConfig 批量删除配置
     * @param $param
     * @return array
     */
    public function batchDelConfig($param){
        Db::startTrans();// 启动事务
        try{
            ConfigModel::destroy($param);
            Db::commit();// 提交事务
            writelog('配置批量删除成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '批量删除成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('配置批量删除失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '批量删除失败'];
        }
    }

    /**
     * [configState 配置状态]
     * @param $param
     * @return array
     */
    public function configState($id,$num){
        $name = $this->where('id',$id)->value('name');
        if($num == 2){
            $msg = '禁用';
        }else{
            $msg = '启用';
        }
        Db::startTrans();// 启动事务
        try{
            $this->where('id',$id)->setField(['status'=>$num]);
            Db::commit();// 提交事务
            writelog('配置【'.$name.'】'.$msg.'成功',200);
//                return ['code' => 200, 'data' => '', 'msg' => '已'.$msg];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('配置【'.$name.'】'.$msg.'失败',100);
            return ['code' => 100, 'data' => '', 'msg' => $msg.'失败'];
        }
    }

    /**
     * configSave 保存配置
     * @param $config
     * @return array
     */
    public function configSave($config){
        Db::startTrans();// 启动事务
        try{
            if($config && is_array($config)){
                foreach ($config as $name => $value) {
                    if($name == "list_rows"){
                        if($value<=0){
                            $value = 1;
                        }
                    }
                    $map = array('name' => $name);
                    $this->where($map)->setField('value', $value);
                }
            }
            Db::commit();// 提交事务
            writelog('配置保存成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '保存成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            writelog('配置保存失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '保存失败'];
        }
    }

    /**
     * 批量禁用配置
     * @param $param
     * @return array
     */
    public function forbiddenConfig($param){
        Db::startTrans();// 启动事务
        try{
            if($param){
                $this->saveAll($param);
            }else{
                $this->where('1=1')->update(['status'=>2]);
            }
            Db::commit();// 提交事务
            writelog('批量禁用配置成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '批量禁用成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('批量禁用配置失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '批量禁用失败'];
        }
    }

    /**
     * 批量启用配置
     * @param $param
     * @return array
     */
    public function usingConfig($param){
        Db::startTrans();// 启动事务
        try{
            if($param){
                $this->saveAll($param);
            }else{
                $this->where('1=1')->update(['status'=>1]);
            }
            Db::commit();// 提交事务
            writelog('批量启用配置成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '批量启用成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('批量启用配置失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '批量启用失败'];
        }
    }

}