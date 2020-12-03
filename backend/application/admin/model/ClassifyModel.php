<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\model;
use think\Model;
use think\Db;

class ClassifyModel extends Model
{
    protected $name = 'classify';
    
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    /**
     * [getMenus 获取全部分类]
     * @author
     */
    public function getMenus($map,$Nowpage,$limits)
    {
        return $this->where($map)->page($Nowpage,$limits)->order('id asc')->select()->toArray();
    }

    /**
     * [getAllMenu 获取全部分类]
     * @author
     */
    public function getAllMenu($map)
    {
        return $this->where($map)->order('id asc')->select()->toArray ();
    }

    /**
     * [insertMenu 添加分类]
     * @author
     */
    public function insertMenu($pid,$title,$sort,$status)
    {
        if(count($pid) == 1){
            Db::startTrans();// 启动事务
            try{

                for($i=0;$i<count($pid);$i++){
                    $t = $title[$i];
//                    $n = strtolower($name[$i]);
//                    $n = $name[$i];
//                    $c = $css[$i];
                    $id = $pid[0];
                    $s = $sort[$i];
                    $param = [
                        'acid'=>1,
                        'pid'=>$id,
                        'title'=>$t,
//                        'name'=>$n,
//                        'css'=>$c,
                        'sort'=>$s,
                        'status'=>$status
                    ];
                    $list[] = $param;
                }
                $this->saveAll($list);
                writelog('多分类添加成功',200);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '添加分类成功'];
            }catch( \Exception $e){
                Db::rollback ();//回滚事务
                writelog('多分类添加失败',100);
                return ['code' => 100, 'data' => '', 'msg' => '分类添加失败'];
            }
        }else{
            Db::startTrans();// 启动事务
            try{
                if(count($pid) != count($title) || count($pid) != count($sort)){
                    return ['code'=>100, 'data' => '','msg'=>'参数格式错误！'];
                }
                for($i=0;$i<count($pid);$i++){
                    $t = $title[$i];
//                    $n = strtolower($name[$i]);
//                    $n = $name[$i];
//                    $c = $css[$i];
                    $id = $pid[$i];
                    $s = $sort[$i];
                    $param = [
                        'acid'=>1,
                        'pid'=>$id,
                        'title'=>$t,
//                        'name'=>$n,
//                        'css'=>$c,
                        'sort'=>$s,
                        'status'=>$status
                    ];
                    $list[] = $param;
                }
                $this->saveAll($list);
                writelog('多分类添加成功',200);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '添加分类成功'];
            }catch( \Exception $e){
                Db::rollback ();//回滚事务
                writelog('多分类添加失败',100);
                return ['code' => 100, 'data' => '', 'msg' => '分类添加失败'];
            }
        }
    }



    /**
     * [editMenu 编辑分类]
     * @author
     */
    public function editMenu($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param, ['id' => $param['id']]);
            Db::commit();// 提交事务
            writelog('分类【'.$param['title'].'】编辑成功',200);
            return ['code' => 200, 'data' => '', 'msg' => '编辑分类成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('分类【'.$param['title'].'】编辑失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '编辑分类失败'];
        }
    }



    /**
     * [getOneMenu 根据分类id获取一条信息]
     * @author
     */
    public function getOneMenu($id)
    {
        return $this->where('id', $id)->find();
    }



    /**
     * [delMenu 删除分类]
     * @author
     */
    public function delMenu($id,$param)
    {
        $title = $this->where('id', $id)->value('title');
        Db::startTrans();// 启动事务
        try{
//            $this->where('id', $id)->delete();
            ClassifyModel::destroy($param);
            Db::commit();// 提交事务
            $ids = implode(',',$param);
            writelog('分类【'.$title.'】删除成功',200);
            return ['code' => 200, 'data' => $ids, 'msg' => '删除分类成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('分类【'.$title.'】删除失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '删除分类失败'];
        }
    }

    /**
     * batchDelMenu 批量删除分类
     * @param $param
     * @return array
     */
    public function batchDelMenu($param){
        Db::startTrans();// 启动事务
        try{
            ClassifyModel::destroy($param);
            Db::commit();// 提交事务
            $ids = implode(',',$param);
            writelog('分类批量删除成功',200);
            return ['code' => 200, 'data' => $ids, 'msg' => '批量删除成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('分类批量删除失败',100);
            return ['code' => 100, 'data' => '', 'msg' => '批量删除失败'];
        }
    }

//    /**
//     * [ruleSort 分类排序]
//     * @param $param
//     * @return array
//     */
//    public function ruleSort($id,$field,$value){
//        Db::startTrans();// 启动事务
//        try{
//            $this->where(['id' => $id ])->setField($field , $value);
//            Db::commit();// 提交事务
//            writelog('分类排序更新成功',200);
//            return ['code' => 200,'data' => '', 'msg' => '排序更新成功'];
//        }catch( \Exception $e){
//            Db::rollback ();//回滚事务
//            writelog('分类排序更新失败',100);
//            return ['code' => 100, 'data' => '', 'msg' => '排序更新失败'];
//        }
//    }

//    public function editField($id,$field,$value){
//        Db::startTrans();// 启动事务
//        try{
//            $this->where(['id' => $id ])->setField($field , $value);
//            Db::commit();// 提交事务
//            writelog('更新字段成功',200);
//            return ['code' => 200,'data' => '', 'msg' => '更新字段成功'];
//        }catch( \Exception $e){
//            Db::rollback ();//回滚事务
//            writelog('更新字段失败',100);
//            return ['code' => 100, 'data' => '', 'msg' => '更新字段失败'];
//        }
//    }

    /**
     * [ruleState 分类状态]
     * @param $param
     * @return array
     */
    public function ruleState($id,$num){
        $title = $this->where('id',$id)->value('title');
        if($num == 2){
            $msg = '禁用';
        }else{
            $msg = '启用';
        }
        Db::startTrans();// 启动事务
        try{
            $this->where('id',$id)->setField(['status'=>$num]);
            Db::commit();// 提交事务
            writelog('分类【'.$title.'】'.$msg.'成功',200);
//                return ['code' => 200, 'data' => '', 'msg' => '已'.$msg];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog('分类【'.$title.'】'.$msg.'失败',100);
            return ['code' => 100, 'data' => '', 'msg' => $msg.'失败'];
        }
    }

}