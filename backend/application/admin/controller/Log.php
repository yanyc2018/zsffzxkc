<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use app\admin\model\LogModel;
use think\Db;
 
class Log extends Base
{

    /**
     * [operate_log 操作日志]
     * @return [type] [description]
     * @author
     */
    public function operate_log()
    {
        $key = input('key');
        $start = input('start');
        $end = input('end');
        $arr=Db::name("admin")->column("id,username"); //获取用户列表
        if(request()->isAjax ()){
            $map = [];
            if($key&&$key!==""){
                $map['admin_id'] =  $key;
            }
            if($start&&$start!==""&&$end=="")
            {
                $map['add_time'] = ['>= time',$start];
            }
            if($end&&$end!==""&&$start=="")
            {
                $map['add_time'] = ['<= time',$end];
            }
            if($start&&$start!==""&&$end&&$end!=="")
            {
                $map['add_time'] = ['between time',[$start,$end]];
            }
            $field=input('field');//字段
            $order=input('order');//排序方式
            if($field && $order){
                $od=$field." ".$order;
            }else{
                $od="add_time desc";
            }
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = input("limit")?input("limit"):10;// 获取总条数;
            $count = Db::name('log')->where($map)->count();//计算总页面
            $lists = Db::name('log')->where($map)->page($Nowpage, $limits)->order($od)->select();
            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }
        $this->assign('val', $key);
        $this->assign("search_user",$arr);
        return $this->fetch();
    }


    /**
     * [del_log 删除日志]
     * @return [type] [description]
     * @author
     */
    public function del_log()
    {
        $id = input('param.id');
        $log = new LogModel();
        $flag = $log->delLog($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * batchDelLog 批量删除日志
     * @return \think\response\Json
     */
    public function batchDelLog(){
        extract(input());
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的记录！']);
        }
        $log = new LogModel();
        $flag = $log->batchDelLog($ids);
        return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
    }
}