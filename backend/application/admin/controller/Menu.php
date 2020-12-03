<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use app\admin\model\MenuModel;
use think\Db;

class Menu extends Base
{	
    /**
     * [index 菜单列表]
     * @return [type] [description]
     * @author
     */
    public function index()
    {
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
            $nav = new \org\Leftnav;
            $menu = new MenuModel();
            $Nowpage = 1;
            $limits = 1000;
            $count = Db::name('auth_rule')->where($map)->count();//计算总页面
            $admin_rule = $menu->getMenus($map, $Nowpage, $limits);
            foreach($admin_rule  as $key=>$vo){
                $admin_rule[$key]['placeholder'] = '';
            }
            $nav->init($admin_rule);
            $lists = $nav->get_tree(0);
            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }
        return $this->fetch('menu/index');
    }

	
    /**
     * [add_rule 添加菜单]
     * @return [type] [description]
     * @author
     */
	public function add_rule()
    {
        if(request()->isPost()){
            extract(input());
            $pid = trim_explode(',',$pid);
            $title = trim_explode(',',$title);
            $name = trim_explode(',',$name);
            $css = trim_explode(',',$css);
            $sort = trim_explode(',',$sort);
            $menu = new MenuModel();
            $flag = $menu->insertMenu($pid,$title,$name,$css,$sort,$status);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('id');
        $nav = new \org\Leftnav;
        $menu = new MenuModel();
        $map = [];
        $admin_rule = $menu->getAllMenu($map);
        $nav->init($admin_rule);
        $lists = $nav->get_tree(0);
        $this->assign ([
            'admin_rule'=>$lists,
            'id'=>$id
        ]);
        return $this->fetch();
    }

    /**
     * [edit_rule 编辑菜单]
     * @return [type] [description]
     * @author
     */
    public function edit_rule()
    {
        $nav = new \org\Leftnav;
        $menu = new MenuModel();
        if(request()->isPost()){
            $param = input('param.');
//            $param['name'] = strtolower($param['name']);
            $flag = $menu->editMenu($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('id');
        $map = [];
        $admin_rule = $menu->getAllMenu($map);
        $nav->init($admin_rule);
        $arr = $nav->get_tree(0);
//        $arr = $nav::rule($admin_rule);
        $this->assign ('admin_rule',$arr);
        $this->assign('menu',$menu->getOneMenu($id));
        return $this->fetch();
    }

    //根据节点查询出所有子节点
    static public $childNode=array();//存放父节点和父节点下面的子节点
    public function findArrayNode($id,$list){
        foreach ($list as $key => $val){
            if ($id==$val['pid']){
                self::$childNode[]=(int)$val['id'];
                self::findArrayNode($val['id'], $list);     //递归，传入新节点ID
            }
        }
    }


    /**
     * [del_menu 删除菜单]
     * @return [type] [description]
     * @author
     */
    public function del_menu()
    {
        $tid = input('param.id');
        $allTree = Db::name('auth_rule')
            ->field('id,pid')
            ->select();
        self::$childNode[]=(int)$tid;
        self::findArrayNode($tid, $allTree);
        $menu = new MenuModel();
        $flag = $menu->delMenu($tid,self::$childNode);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }


    /**
     * editField 快捷编辑
     * @return \think\response\Json
     */
    public function editField(){
        extract(input());
        $res = Db::name($table)->where(['id' => $id ])->setField($field , $value);
        if($res){
            writelog('更新字段成功',200);
            return json(['code' => 200,'data' => '', 'msg' => '更新字段成功']);
        }else{
            writelog('更新字段失败',100);
            return json(['code' => 100,'data' => '', 'msg' => '更新字段失败']);
        }
    }

    /**
     * [rule_state 菜单状态]
     * @return [type] [description]
     * @author
     */
    public function rule_state()
    {
        extract(input());
        $menu = new MenuModel();
        $flag = $menu->ruleState($id,$num);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * batchDelMenu 批量删除菜单
     * @return \think\response\Json
     */
    public function batchDelMenu(){
        extract(input());
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的记录！']);
        }
        $ids = explode(',',$ids);
        $allTree = Db::name('auth_rule')
            ->field('id,pid')
            ->select();
        foreach($ids as $key=>$vo){
            self::$childNode[]=(int)$vo;
            self::findArrayNode($vo, $allTree);
        }
        $menu = new MenuModel();
        $flag = $menu->batchDelMenu(array_unique(self::$childNode));
        return json(['code' => $flag['code'],'data' => $flag['data'],'msg' => $flag['msg']]);
    }

}