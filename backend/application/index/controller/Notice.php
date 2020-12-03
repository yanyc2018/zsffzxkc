<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\index\controller;
use app\index\controller\Base;
use think\Controller;
use think\Db;
class Notice extends  Base
{
    public function newnotice(){
        $map=array(
          'acid'=>1,
        );
        $data=Db::name('gonggao')
            ->where($map)
            ->select();
        foreach ($data as $k=>$v){
            $data[$k]['year']=date('Y',$v['addtime']);
            $data[$k]['day']=date('m-d',$v['addtime']);
            $data[$k]['time']=date('H:i',$v['addtime']);
        }
        $this->result(0,'获取通知数据成功',$data,0,'json');
    }
}
