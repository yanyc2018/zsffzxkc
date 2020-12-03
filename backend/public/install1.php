<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
session_start();
if($_POST['form']=='form1'){
    $servername =$_POST['hostname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    try {
        $conn = new PDO("mysql:host=$servername;", $username, $password);

        $_SESSION['hostname']=$_POST['hostname'];
        $_SESSION['database']=$_POST['database'];
        $_SESSION['username']=$_POST['username'];
        $_SESSION['password']=$_POST['password'];
        echo json_encode(['code'=>0,'msg'=>'连接成功']);
    }
    catch(PDOException $e)
    {
        echo json_encode(['code'=>1,'msg'=>'数据库连接失败']);
    }
}
if($_POST['form']=='form2'){
    $path= __DIR__. '/../application/';
    if(is_writable($path)){
        echo json_encode(['code'=>0,'msg'=>'可写','data'=>$path]);
    }else{
        echo json_encode(['code'=>1,'msg'=>'不可写']);
    }
}
if($_POST['form']=='form3'){
    $data ="<?php
            return [
                // 数据库类型
                'type'            => 'mysql',
                // 服务器地址
                'hostname'        => '{$_SESSION['hostname']}',
                // 数据库名
                'database'        => '{$_SESSION['database']}',
                // 用户名
                'username'        => '{$_SESSION['username']}',
                // 密码
                'password'        => '{$_SESSION['password']}',
                // 端口
                'hostport'        => '3306',
                // 连接dsn
                'dsn'             => '',
                // 数据库连接参数
                'params'          => [],
                // 数据库编码默认采用utf8
                'charset'         => 'utf8',
                // 数据库表前缀
                'prefix'          => 'jy_',
                // 数据库调试模式
                'debug'           => true,
                // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
                'deploy'          => 0,
                // 数据库读写是否分离 主从式有效
                'rw_separate'     => false,
                // 读写分离后 主服务器数量
                'master_num'      => 1,
                // 指定从服务器序号
                'slave_no'        => '',
                // 是否严格检查字段是否存在
                'fields_strict'   => true,
                // 数据集返回类型
                'resultset_type'  => '\\think\Collection',
                // 自动写入时间戳字段
                'auto_timestamp'  => true,
                // 时间字段取出后的默认时间格式
                'datetime_format' => 'Y-m-d H:i',
                // 是否需要进行SQL性能分析
                'sql_explain'     => true,
                // 开启断线重连
                'break_reconnect' => true,
            ];";
    $numbytes = file_put_contents(__DIR__. '/../application/database.php', $data); //如果文件不存在创建文件，并写入内容
    if($numbytes){
        $mysqli = new mysqli($_SESSION['hostname'],$_SESSION['username'],$_SESSION['password'],$_SESSION['database'],3306);  //连接数据库
        if($mysqli){
            $query = file_get_contents('./zsffzxkc.sql');
            if($mysqli->multi_query($query)){                           //执行多条SQL语句
                echo json_encode(['code'=>0,'msg'=>'安装成功']);
                file_put_contents(__DIR__. '/install.lock', '安装锁定文件,请勿删除');
            }else{
                echo json_encode(['code'=>1,'msg'=>'安装失败','data'=>"ERROR".$mysqli->errno."---".$mysqli->error]);
            }
            $mysqli->close();//关闭mysqli连接
        }else{
            echo json_encode(['code'=>1,'msg'=>'数据库连接失败']);
        }
    }else{
        echo json_encode(['code'=>1,'msg'=>'权限不足,写入失败']);
    }
}