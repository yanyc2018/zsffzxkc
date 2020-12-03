<?php

namespace app\admin\controller;
use app\admin\model\ClassifyModel;
use think\Db;

class Classify extends Base
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
            $menu = new ClassifyModel();
            $Nowpage = 1;
            $limits = 1000;
            $count = Db::name('classify')->where($map)->count();//计算总页面
            $admin_rule = $menu->getMenus($map, $Nowpage, $limits);
            foreach($admin_rule  as $key=>$vo){
                $admin_rule[$key]['placeholder'] = '';
            }
            if(!empty($admin_rule)){
                $nav->init($admin_rule);
                $lists = $nav->get_tree(0);
            }else{
                $lists =$admin_rule;
            }

            return json(['code'=>220,'msg'=>'','count'=>$count,'data'=>$lists]);
        }
        return $this->fetch('classify/index');
    }
    public function classify_list_json(){
        $map2=array(
            'status'=>1,
            'acid'=>1,
            'pid'=>0
        );
        $classify=Db::name('classify')
            ->where($map2)
            ->select();
        foreach ($classify as $k=>$v){
            $list[$k]['value']=$v['id'];
            $list[$k]['label']=$v['title'];
            $child=Db::name('classify')
                ->where([
                    'status'=>1,
                    'acid'=>1,
                    'pid'=>$v['id']
                ])
                ->select();
            if (!empty($child)){
                foreach ($child as $key=>$val){
                    $list1[$key]['value']=$val['id'];
                    $list1[$key]['label']=$val['title'];
                }
                $list[$k]['children']=$list1;
            }
        }
        $this->result(0,'成功',$list,0);
    }
	
    /**
     * [add_rule 添加菜单]
     * @return [type] [description]
     * @author
     */
	public function add_classify()
    {
        if(request()->isPost()){
            extract(input());
            $pid = trim_explode(',',$pid);
            $title = trim_explode(',',$title);
//            $name = trim_explode(',',$name);
//            $css = trim_explode(',',$css);
            $sort = trim_explode(',',$sort);
            $menu = new ClassifyModel();
            $flag = $menu->insertMenu($pid,$title,$sort,$status);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('id');
        $nav = new \org\Leftnav;
        $menu = new ClassifyModel();
        $map = [];
        $admin_rule = $menu->getAllMenu($map);

        if(!empty($admin_rule)){
            $nav->init($admin_rule);
            $lists = $nav->get_tree(0);
        }else{
            foreach($admin_rule  as $key=>$vo){
                $admin_rule[$key]['placeholder'] = '';
            }
            $lists =$admin_rule;
        }
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
    public function edit_classify()
    {
        $nav = new \org\Leftnav;
        $menu = new ClassifyModel();
        if(request()->isPost()){
            $param = input('param.');
            $param['update_time'] = time();
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
        $allTree = Db::name('classify')
            ->field('id,pid')
            ->select();
        self::$childNode[]=(int)$tid;
        self::findArrayNode($tid, $allTree);
        $menu = new ClassifyModel();
        $flag = $menu->delMenu($tid,self::$childNode);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }


    /**
     * editField 快捷编辑
     * @return \think\response\Json
     */
    public function editField(){
        extract(input());
        $data=array(
            $field=>$value,
            'update_time'=>time()
        );
        $res = Db::name('classify')->where(['id' => $id ])->update($data);
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
        $menu = new ClassifyModel();
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
        $allTree = Db::name('classify')
            ->field('id,pid')
            ->select();
        foreach($ids as $key=>$vo){
            self::$childNode[]=(int)$vo;
            self::findArrayNode($vo, $allTree);
        }
        $menu = new ClassifyModel();
        $flag = $menu->batchDelMenu(array_unique(self::$childNode));
        return json(['code' => $flag['code'],'data' => $flag['data'],'msg' => $flag['msg']]);
    }

}