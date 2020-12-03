<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\model;
use think\Model;
use think\Db;

class Node extends Model
{

    protected $name = "auth_rule";


    /**
     * [getNodeInfo 获取节点数据]
     * @author
     */
    public function getNodeInfo($id)
    {
        $result = $this->field('id,title,pid,sort')->select()->toArray ();
        for($i=0;$i<count($result);$i++){
            $sort[] = $result[$i]['sort'];
        }
        array_multisort($sort, SORT_ASC, $result);
        $str = "";
        $role = new UserType();
        $rule = $role->getRuleById($id);

        if(!empty($rule)){
            $rule = explode(',', $rule);
        }
        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['title'].'"';

            if(!empty($rule) && in_array($vo['id'], $rule)){
                $str .= ' ,"checked":1';
            }

            $str .= '},';
        }

        return "[" . substr($str, 0, -1) . "]";
    }


    /**
     * [getMenu 根据节点数据获取对应的菜单]
     * @author
     */
    public function getMenu($nodeStr="")
    {
        //超级管理员没有节点数组
        if($nodeStr == "SUPERAUTH"){
            if(session('?security') && session('security') == 010){
                $where = array();
            }else{
                $where = 'status = 1';
            }
            $result = Db::name('auth_rule')->where($where)->order('sort')->select();
        }elseif(empty($nodeStr)){
            $result = array();
        }else{
            $where = 'status = 1 and id in('.$nodeStr.')';
            $result = Db::name('auth_rule')->where($where)->order('sort')->select();
        }
//        $where = empty($nodeStr) ? array() : 'status = 1 and id in('.$nodeStr.')';

        $menu = prepareMenu($result);
        return $menu;
    }
}