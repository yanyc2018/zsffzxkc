<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use think\Config;
use think\Db;
use think\Session;
use think\Request;
use com\Database;

class Data extends Base
{
    /**
     * 数据备份首页
     * @author
     */
    public function index() {
        if(request()->isAjax ()){
            $tmp = Db::query('SHOW TABLE STATUS');
            $data = array_map('array_change_key_case', $tmp);
            foreach($data as $key=>$vo){
                if($vo['name'] == config('database.prefix')."backups"){
                    unset($data[$key]);
                }
                if($vo['name'] == config('database.prefix')."log"){
                    unset($data[$key]);
                }
            }
            $data = array_merge ($data);;
            return json(['code'=>220,'msg'=>'','count'=>count($data),'data'=>$data]);
        }
        return $this->fetch("data/index");
    }



    /**
     * 备份数据库
     * @param  String  $ids 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     * @author
     */
    public function export($ids = null, $id = null, $start = null) {
        $Request = Request::instance();
        if ($Request->isPost() && !empty($ids) && is_array($ids)) { //初始化
            $path = Config::get('data_backup_path');
            is_dir($path) || mkdir($path, 0755, true);
            //读取备份配置
            $config = [
                'path' => realpath($path) . DIRECTORY_SEPARATOR,
                'part' => Config::get('data_backup_part_size'),
                'compress' => Config::get('data_backup_compress'),
                'level' => Config::get('data_backup_compress_level'),
            ];

            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
//            if (is_file($lock)) {
//                return $this->error('检测到有一个备份任务正在执行，请稍后再试！');
//            }
            file_put_contents($lock, $Request->time()); //创建锁文件
            //检查备份目录是否可写
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
            Session::set('backup_config', $config);
            //生成备份文件信息
            $file = [
                'name' => date('Ymd-His', $Request->time()),
                'part' => 1
            ];
            Session::set('backup_file', $file);
            //缓存要备份的表
            Session::set('backup_tables', $ids);
            //创建备份文件
            $Database = new \com\Database($file, $config);
            if (false !== $Database->create()) {
                $tab = ['id' => 0, 'start' => 0];
                return $this->success('初始化成功！', '', ['tables' => $ids, 'tab' => $tab]);
            } else {
                return $this->error('初始化失败，备份文件创建失败！');
            }
        } elseif ($Request->isGet() && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = Session::get('backup_tables');
            //备份指定表
            $Database = new \com\Database(Session::get('backup_file'), Session::get('backup_config'));
            $start = $Database->backup($tables[(int) $id], $start);
            if (false === $start) { //出错
                $this->error('备份出错！');
            } elseif (0 === $start) { //下一表
                if (isset($tables[++$id])) {
                    $tab = ['id' => $id, 'start' => 0];
                    return $this->success('备份完成！', '', ['tab' => $tab]);
                } else { //备份完成，清空缓存
                    unlink(Session::get('backup_config.path') . 'backup.lock');
                    $tab = implode ('|',session('backup_tables'));
                    $time = session('backup_file')['name'];
                    writelog('备份数据库【'.$time.'】成功',200);
                    Session::set('backup_tables', null);
                    Session::set('backup_file', null);
                    Session::set('backup_config', null);
                    return $this->success('备份完成！');
                }
            } else {
                $tab = ['id' => $id, 'start' => $start[0]];
                $rate = floor(100 * ($start[0] / $start[1]));
                return $this->success("正在备份...({$rate}%)", '', ['tab' => $tab]);
            }
        } else {
            return json(['msg' => '请选择要备份的数据表！']);
        }
    }

    /**
     * 优化表
     * @param  String $ids 表名
     */
    public function optimize($ids = null) {
        if (empty($ids)) {
            return $this->error("请指定要优化的表！");
        }
        $table = trim_explode(',',$ids);
        $Db = Db::connect();
        if (count($table) > 1) {
            $tables = implode('`,`', $table);
            $list = $Db->query("OPTIMIZE TABLE `{$tables}`");
            if($list){
                writelog('用户【'.session('username').'】优化数据库成功',200);
//                $this->success("数据表优化完成！");
                return json(['code'=>200,'msg'=>"数据表优化完成！"]);
            } else {
                writelog('用户【'.session('username').'】优化数据库失败',100);
//                $this->error("数据表优化出错请重试！");
                return json(['code'=>100,'msg'=>"数据表优化出错请重试！"]);
            }
        } else {

            $list = $Db->query("OPTIMIZE TABLE `{$ids}`");
            if($list){
//                $this->success("数据表'{$ids}'优化完成！");
                writelog('用户【'.session('username').'】优化【'.$ids.'】表成功',200);
                return json(['code'=>200,'msg'=>"数据表'{$ids}'优化完成！"]);
                //return json("数据表'{$ids}'优化完成！");
            } else {
//                $this->error("数据表'{$ids}'优化出错请重试！");
                writelog('用户【'.session('username').'】优化【'.$ids.'】表失败',100);
                return json(['code'=>100,'msg'=>"数据表'{$ids}'优化出错请重试！"]);
            }
        }
    }



    /**
     * 修复表
     * @param  String $ids 表名
     * @author
     */
    public function repair($ids = null) {
        if (empty($ids)) {
            return $this->error("请指定要修复的表！");
        }
        $table = trim_explode(',',$ids);
        $Db = Db::connect();
        if (count($table) > 1) {
            $tables = implode('`,`', $table);
            $list = $Db->query("REPAIR TABLE `{$tables}`");
            if($list){
                writelog('用户【'.session('username').'】修复数据库成功',200);
//                $this->success("数据表修复完成！");
                return json(['code'=>200,'msg'=>"数据表修复完成！"]);
            } else {
                writelog('用户【'.session('username').'】修复数据库失败',100);
//                $this->error("数据表修复出错请重试！");
                return json(['code'=>100,'msg'=>"数据表修复出错请重试！"]);
            }
        } else {
            $list = $Db->query("REPAIR TABLE `{$ids}`");
            if($list){
//                $this->success("数据表'{$ids}'修复完成！");
                writelog('用户【'.session('username').'】修复【'.$ids.'】表成功',200);
                return json(['code'=>200,'msg'=>"数据表'{$ids}'修复完成！"]);
            } else {
//                $this->error("数据表'{$ids}'修复出错请重试！");
                writelog('用户【'.session('username').'】修复【'.$ids.'】表失败',100);
                return json(['code'=>100,'msg'=>"数据表'{$ids}'修复出错请重试！"]);
            }
        }
    }




    /**
     * 还原数据库首页
     * @param 类型 参数 参数说明
     * @author staitc7 <static7@qq.com>
     */
    public function import() {
        if(request()->isAjax ()) {
            //列出备份文件列表
            $path_tmp = Config::get ('data_backup_path');
            is_dir ($path_tmp) || mkdir ($path_tmp , 0755 , true);
            $path = realpath ($path_tmp);
            $flag = \FilesystemIterator::KEY_AS_FILENAME;
            $glob = new \FilesystemIterator($path , $flag);
            $list = array();
            foreach ($glob as $name => $file) {
                if ( preg_match ('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/' , $name) ) {
                    $name = sscanf ($name , '%4s%2s%2s-%2s%2s%2s-%d');
                    $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                    $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                    $part = $name[ 6 ];
                    if ( isset($list[ "{$date} {$time}" ]) ) {
                        $info = $list[ "{$date} {$time}" ];
                        $info[ 'part' ] = max ($info[ 'part' ] , $part);
                        $info[ 'size' ] = $info[ 'size' ] + $file->getSize ();
                    } else {
                        $info[ 'part' ] = $part;
                        $info[ 'size' ] = format_bytes($file->getSize ());
                    }
                    $extension = strtoupper (pathinfo ($file->getFilename () , PATHINFO_EXTENSION));
                    $info[ 'compress' ] = ($extension === 'SQL') ? '-' : $extension;
                    $info[ 'time' ] = date('Ymd-His' ,strtotime ("{$date} {$time}"));
                    $info['date'] = "{$date} {$time}" ;
                    $list[] = $info;
                }
            }
            return json (['code' => 220 , 'msg' => '' , 'count' => count($list) , 'data' => arrayToSort($list,'date','SORT_DESC')]);
        }
        return $this->fetch('data/import');
    }

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     * @author
     */
    public function delData() {
        $time = input('id');
        empty($time) && $this->error('参数错误！');
        $name = $time . '-*.sql*';
        $path = realpath(Config::get('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
        array_map("unlink", glob($path));
        if (count(glob($path))) {
            writelog('删除【'.$time.'】备份文件失败',100);
//            return $this->error('备份文件删除失败，请检查权限！');
            return json(['code'=>100,'msg'=>'备份文件删除失败，请检查权限！']);
        } else {
            writelog('删除【'.$time.'】备份文件成功',200);
//            return $this->success('备份文件删除成功！');
            return json(['code'=>200,'msg'=>'备份文件删除成功!']);
        }
    }

    /**
     * 还原数据库
     * @author
     */
    public function revert($time = 0, $part = null, $start = null) {
        if (!empty($time) && is_null($part) && is_null($start)) { //初始化
            //获取备份文件信息
            $name = $time . '-*.sql*';
            $path = realpath(Config::get('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list = [];
            foreach ($files as $name) {
                $basename = basename($name);
                $match = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);
            $last = end($list);//检测文件正确性
            if (count($list) === $last[0]) {
                Session::set('backup_list', $list); //缓存备份列表
                return $this->success('初始化完成,请等待！', '', ['part' => 1, 'start' => 0]);
            } else {
                return $this->error('备份文件可能已经损坏，请检查！');
            }
        } elseif (is_numeric($part) && is_numeric($start)) {
            set_time_limit(0);
            $list = Session::get('backup_list');
            $db = new \com\Database($list[$part], [
                    'path' => realpath(Config::get('data_backup_path')) . DIRECTORY_SEPARATOR,
                    'compress' => $list[$part][2]
                ]
            );
            $start = $db->import($start);
            if (false === $start) {
                writelog('还原数据库【'.date('Ymd-His',$time).'】失败',100);
                return $this->error('还原数据出错！');
            } elseif (0 === $start) { //下一卷
                if (isset($list[++$part])) {
                    $data = array('part' => $part, 'start' => 0);
                    $this->success("正在还原... ", '', $data);
                } else {
                    writelog('还原数据库【'.date('Ymd-His',$time).'】成功',200);
                    Session::set('backup_list', null);
                    $this->success('数据库还原完成！','', 'success');
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if ($start[1]) {
                    $rate = floor(100 * ($start[0] / $start[1]));
                    return $this->success("正在还原... ({$rate}%)", '', $data);
                } else {
                    $data['gz'] = 1;
                    return $this->success("正在还原... ", '', $data);
                }
            }
        } else {
            return $this->error('参数错误！');
        }
    }

    /**
     * batchDelData 批量删除备份文件
     * @return \think\response\Json
     */
    public function batchDelData(){
        extract(input());
        if(empty($ids)){
            return json(['code'=>100,'msg'=>'请选择要删除的备份文件']);
        }
        $ids = trim_explode(',',$ids);
        $error = [];//储存删除失败文件
        $success = [];//储存删除成功文件
        foreach($ids as $vo){
            $name = $vo . '-*.sql*';
            $path = realpath(Config::get('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if (count(glob($path))) {
                $error[] = $vo;
            }else{
                $success[] = $vo;
            }
        }
        if (!empty($error)) {
            $error = implode (',',$error);
            writelog('批量删除【'.$error.'】备份文件失败',100);
            return json(['code'=>100,'msg'=>'批量删除失败']);
        } else {
            writelog('批量删除备份文件成功',200);
            return json(['code'=>200,'data'=>$success,'msg'=>'批量删除成功']);
        }
    }

}