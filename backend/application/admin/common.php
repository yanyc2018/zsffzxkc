<?php
use think\Db;

/**
 * 压缩图片
 * @param $imgsrc 压缩图片地址
 * @param $imgdst 生成地址
 */
function image_png_size_add($imgsrc,$imgdst){
    list($width,$height,$type)=getimagesize($imgsrc);
//    $new_width = ($width>600?600:$width)*0.9;
//    $new_height =($height>600?600:$height)*0.9;
    $new_width = $width;
    $new_height =$height;
    switch($type){
        case 1:
            break;
        case 2:
            header('Content-Type:image/jpeg');
            $image_wp=imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefromjpeg($imgsrc);
            imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($image_wp, $imgdst,config('quality'));
            imagedestroy($image_wp);
            break;
        case 3:
            header('Content-Type:image/png');
            $image_wp=imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefrompng($imgsrc);
            imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($image_wp, $imgdst,config('quality'));
            imagedestroy($image_wp);
            break;
    }
}

/**
 * 将字符解析成数组
 * @param $str
 */
function parseParams($str)
{
    $arrParams = [];
    parse_str(html_entity_decode(urldecode($str)), $arrParams);
    return $arrParams;
}


/**
 * 子孙树 用于菜单整理
 * @param $param
 * @param int $pid
 */
function subTree($param, $pid = 0)
{
    static $res = [];
    foreach($param as $key=>$vo){

        if( $pid == $vo['pid'] ){
            $res[] = $vo;
            subTree($param, $vo['id']);
        }
    }

    return $res;
}


/**
 * 记录日志
 * @param  [type] $uid         [用户id]
 * @param  [type] $username    [用户名]
 * @param  [type] $description [描述]
 * @param  [type] $status      [状态] 200 操作成功  100 操作失败
 * @param  [type] $type        [删除日志启用]
 *///$uid,$username,$description,$status,$type=''
function writelog($description,$status,$uid = '',$username = '',$type='')
{
    $id = $uid ? $uid : session('uid');
    $name = $username ? : session('username');
    $ip = request()->ip();
    $ipaddr = get_ip_area($ip);//根据ip地址获取地域信息
    /****************************日志存入数据库*******************************/
    if($type == ''){
        $data['admin_id'] = $id;
        $data['admin_name'] = $name;
        $data['description'] = $description;
        $data['status'] = $status;
        $data['ip'] = $ip;
        $data['add_time'] = time();
        $data['ipaddr'] = $ipaddr;
        $logId = Db::name('Log')->insertGetId($data);
    }else{
        $logId = '空';
    }
    /****************************日志存入数据库*******************************/

    /****************************日志存入文件*******************************/
    if(config('log_std')){
        $dir = 'log/'.date('Ymd',time());
        if(!is_dir($dir)){
            if(!mkdir($dir,0777,true)){
                return false;
            }
        }
        $file = 'log/'.date('Ymd',time()).'/'.date('Ymd',time()).'.txt';
        if(!is_file($file)){
            $word=fopen($file,"a+");
            //        fwrite($word,' ID +----------+ 用户ID +----------+ 操作用户 +----------+ 描述 +----------+ 操作IP +----------+ 地址 +----------+ 状态 +----------+ 操作时间');
            fwrite($word,"\r\n".'+-------------+--------------+--------------+----------------------------------------------------------+----------------------+-------------------+-----------------+-----------------------+');
            fwrite($word,"\r\n".'|             |              |              |                                                          |                      |                   |                 |                       |');
            fwrite($word,"\r\n".'|     ID      |    用户ID    |   操作用户   |                     描述                                 |        操作IP        |        地址       |      状态       |        操作时间       |');
            fwrite($word,"\r\n".'|             |              |              |                                                          |                      |                   |                 |                       |');
            fwrite($word,"\r\n".'+-------------+--------------+--------------+----------------------------------------------------------+----------------------+-------------------+-----------------+-----------------------+');
            fclose($word);
        }
        $add_time=date('Y-m-d H:i:s',time());
        if($status == 200){
            $sta = '成功';
        }else{
            $sta = '失败';
        }
        file_put_contents($file,  "\r\n ".$logId.' +----------+ '.$uid.' +----------+ '.$username.' +----------+ '.$description.' +----------+ '.$ip.' +----------+ '.$ipaddr.' +----------+ '.$sta.' +----------+ '.$add_time,FILE_APPEND);
        //    file_put_contents($file,  "\r\n".'| '.$logId.'          '.$uid.'              '.$username.'         '.$description.'                                       '.$ip.'              '.$ipaddr['country'].$ipaddr['place'].'            '.$sta.'              '.$add_time.'    |',FILE_APPEND);
        file_put_contents($file,  "\r\n".'+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+',FILE_APPEND);
    }
    /****************************日志存入文件*******************************/
}


/**
 * 整理菜单树方法
 * @param $param
 * @return array
 */
function prepareMenu($param)
{
//    dump($param);die;
    $parent = []; //父类
    $child = [];  //子类
    foreach($param as $key=>$vo) {
        if ( $vo[ 'pid' ] == 0 && $vo[ 'name' ] == '#' ) {
            $vo[ 'href' ] = '#';
            $parent[] = $vo;
        }else if($vo[ 'pid' ] == 0 && $vo[ 'name' ] != '#' ){
            if(!preg_match ("/^((https|http|ftp|rtsp|mms){0,1}(:\/\/){0,1})www\.(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])+$/",$vo['name'])){
                $vo[ 'href' ] = url($vo['name']); //跳转地址
            }else{
                $vo[ 'href' ] = $vo['name']; //跳转地址
            }
            $parent[] = $vo;
        }else{
            if(!preg_match ("/^((https|http|ftp|rtsp|mms){0,1}(:\/\/){0,1})www\.(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])+$/",$vo['name'])){
                $vo[ 'href' ] = url($vo['name']); //跳转地址
            }else{
                $vo[ 'href' ] = $vo['name']; //跳转地址
            }
            $child[] = $vo;
        }
    }

    foreach($parent as $key=>$vo){
        foreach($child as $k=>$v){
            if($v['pid'] == $vo['id']){
                $parent[$key]['child'][] = $v;
            }
        }
    }

    for($i=0;$i<count($parent);$i++){
        if(isset($parent[$i]['child'])){
            for($j=0;$j<count($parent[$i]['child']);$j++){
                if($parent[$i]['child'][$j]['name'] == '##'){
                    for($a=0;$a<count($child);$a++){
                        if($child[$a]['pid'] == $parent[$i]['child'][$j]['id']){
                            $parent[$i]['child'][$j]['child'][] = $child[$a];
                        }
                    }
                }
            }
        }
    }
    unset($child);
    return $parent;
}


/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }
    return $size . $delimiter . $units[$i];
}

// 分析枚举类型配置值 格式 a:名称1,b:名称2
function parse_config_attr($string) {
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if(strpos($string,':')){
        $value  =   array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k]   = $v;
        }
    }else{
        $value  =   $array;
    }
    return $value;
}

/**
 * trim & explode
 * @param $d 符号
 * @param $str 字符串
 * @return array
 */
function trim_explode($d,$str){
    $str = trim($str,$d);
    $res = explode($d,$str);
    return $res;
}