<?php
namespace org;

class leftnav{


	static public function rule($cate , $lefthtml = '— — ' , $pid=0 , $lvl=0, $leftpin=0 ){
		$arr=array();
		for($i=0;$i<count($cate);$i++){
		    $sort[] = $cate[$i]['sort'];
        }
        array_multisort($sort, SORT_ASC, $cate);
		foreach ($cate as $v){
			if($v['pid']==$pid){
				$v['lvl']=$lvl + 1;
				$v['leftpin']=$leftpin + 0;//左边距
//				$v['lefthtml']=str_repeat($lefthtml,$lvl);
				$v['title'] = str_repeat($lefthtml,$lvl).$v['title'];
				$arr[]=$v;
				$arr= array_merge($arr,self::rule($cate,$lefthtml,$v['id'],$lvl+1 , $leftpin+20));
			}
		}
		return $arr;
	}

    /**
     * 得到树型结构
     * @param int ID，表示获得这个ID下的所有子级
     * @param string 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
     * @param int 被选中的ID，比如在做树型下拉框的时候需要用到
     * @return string
     */
    public function get_tree($myid, $str="", $sid = 0, $adds = '', $str_group = '')
    {
        $number = 1;
        //一级栏目
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $value) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';
                $selected = $id == $sid ? 'selected' : '';
                extract($value);
                $pid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
//                $value['title'] = $spacer.$value['title'];
                $value['placeholder'] = $spacer;
                $this->end[] = $value;
//                $this->ret .= $nstr;
                $nbsp = $this->nbsp;
                $this->get_tree($id, $str, $sid, $adds . $k . $nbsp, $str_group);
                $number++;
            }
        }
        return $this->end;
    }

    public $end = array();


    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    public function get_child($myid)
    {
        $a = $newarr = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a['pid'] == $myid) {
                    $newarr[$id] = $a;
                }
            }
        }
        return $newarr ? $newarr : false;
    }

    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    public $arr = array();


    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    public $icon = array('&nbsp;│&nbsp;', '&nbsp;├─&nbsp;', '&nbsp;└─&nbsp;');
    public $nbsp = "&nbsp;";

    /**
     * @access private
     */
    public $ret = '';

    public function init($arr = array())
    {
        for($i=0;$i<count($arr);$i++){
            $sort[] = $arr[$i]['sort'];
        }
        array_multisort($sort, SORT_ASC, $arr);
        $this->arr = $arr;
        $this->ret = '';
        return is_array($arr);
    }
	
}