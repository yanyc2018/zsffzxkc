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
class Set extends  Base
{
    public function base_set(){
        $map=array(
            'acid'=>1,
        );
        $data=Db::name('set')
            ->where($map)
            ->find();
        $smallapp=Db::name('set_smallapp')
            ->where($map)
            ->find();
        if(!empty($smallapp)){
            $data['smallapp']=$smallapp;
        }
        $h5=Db::name('set_h5')
            ->where($map)
            ->find();
        if(!empty($h5)){
            $data['h5']=$h5;
        }
        $this->result(0,'获取基础设置数据成功',$data,0,'json');
    }
}
