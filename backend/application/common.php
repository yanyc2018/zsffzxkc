<?php
use think\Db;
use yzx\Ucpaas;
use aldy\SmsDemo;
/**
 * 字符串截取，支持中文和其他编码
 * @param $str 截取对象
 * @param int $start 开始位置
 * @param $length 长度
 * @param string $charset 编码
 * @param bool $suffix 是否加省略号
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = false) {
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
        if (false === $slice) {
            $slice = '';
        }
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice . '...' : $slice;
}

/**
 * 调用系统的API接口方法（静态方法）
 * api('User/getName','id=5'); 调用公共模块的User接口的getName方法
 * api('Admin/User/getName','id=5');  调用Admin模块的User接口
 * @param  string  $name 格式 [模块名]/接口名/方法名
 * @param  array|string  $vars 参数
 */
function api($name,$vars=array()){
    $array     = explode('/',$name);
    $method    = array_pop($array);
    $classname = array_pop($array);
    $module    = $array? array_pop($array) : 'common';
    $callback  = 'app\\'.$module.'\\Api\\'.$classname.'Api::'.$method;
    if(is_string($vars)) {
        parse_str($vars,$vars);
    }
    return call_user_func_array($callback,$vars);
}


/**
 * 获取配置的分组
 * @param string $group 配置分组
 * @return string
 */
function get_config_group($group=0){
    $list = config('config_group_list');
    return $group?$list[$group]:'';
}

/**
 * 获取配置的类型
 * @param string $type 配置类型
 * @return string
 */
function get_config_type($type=0){
    $list = config('config_type_list');
    return $list[$type];
}

/*
 * alisms 阿里大于验证码
 * @param $phoneNum 手机号
 * @param $code验证码
 * @param $TemplateCode 模板id
 * return array Code: OK  Message: OK
 */
function alisms($phoneNum,$code,$TemplateCode=''){
    header('Content-Type: text/plain; charset=utf-8');
    $Alidy = new SmsDemo();
    if($TemplateCode == ''){
        $TemplateCode = config('alidy.TemplateCode');
    }
    $result = $Alidy->sendSms($phoneNum,config('alidy.SignName'),$TemplateCode,$code);
    $result = object_array($result);
    return $result;
}

/**
 * YzxSms 云之讯短信
 * @param $param 验证码
 * @param $mobile 手机号
 * @param $uid 用户透传id
 * @param $templateid 模板id
 * @return array  code:000000 msg:OK
 */
function YzxSms($param,$mobile,$templateid='',$uid=1){
    $appid = config('yzx.appid');
    if($templateid == ''){
        $templateid = config('yzx.templateid');
    }
    $options = ['accountsid'=>config('yzx.accountsid'),'token'=>config('yzx.token')];
    $yzxsms = new Ucpaas($options);
    $result = $yzxsms->SendSms($appid,$templateid,$param,$mobile,$uid);
    $result = json_decode($result,true);
    return $result;
}


/**
 * GetRandCode 获取随机6位数字验证码
 * @param int $len
 * @return string
 */
function GetRandCode($len = 6)
{
    $chars = array(
        "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = "";
    for ($i=0; $i<$len; $i++)
    {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}


/**
 * 删除文件夹及文件夹内文件函数，支持中文路径
 * @param string $path 文件夹路径
 * @param string $delDir 是否删除该文件夹
 * @return boolean
 */
function delete_dir_file($path, $del = true){
    $result = false;
    if(is_dir($path)) {
        $path = iconv ("UTF-8" , "GBK" , $path);
        $handle = opendir ($path);
        if ( $handle ) {
            while (false !== ($item = readdir ($handle))) {
                if ( ($item != ".") && ($item != "..") ) {
                    is_dir ("$path/$item") ? delete_dir_file ("$path/$item" , $del) : unlink ("$path/$item");
                }
            }
            closedir ($handle);
            if ( $del ) {
                rmdir ($path);
            }
            $result = true;
        }
    }
    return $result;
}


/**
 * formatTime 时间格式化1
 * @param $time 时间戳
 * @return string
 */
function formatTime($time) {
    $now_time = time();
    $t = $now_time - $time;
    $mon = (int) ($t / (86400 * 30));
    if ($mon >= 1) {
        return '一个月前';
    }
    $day = (int) ($t / 86400);
    if ($day >= 1) {
        return $day . '天前';
    }
    $h = (int) ($t / 3600);
    if ($h >= 1) {
        return $h . '小时前';
    }
    $min = (int) ($t / 60);
    if ($min >= 1) {
        return $min . '分钟前';
    }
    return '刚刚';
}

/**
 * pincheTime 时间格式化2
 * @param $time 时间戳
 * @return string
 */
function pincheTime($time) {
    $today  =  strtotime(date('Y-m-d')); //今天零点
    $here   =  (int)(($time - $today)/86400) ;
    if($here==1){
        return '明天';
    }
    if($here==2) {
        return '后天';
    }
    if($here>=3 && $here<7){
        return $here.'天后';
    }
    if($here>=7 && $here<30){
        return '一周后';
    }
    if($here>=30 && $here<365){
        return '一个月后';
    }
    if($here>=365){
        $r = (int)($here/365).'年后';
        return   $r;
    }
    return '今天';
}


/**
 * whatWeek 判断日期是星期几
 * @param $date 日期
 * @return string
 */
function whatWeek($date){
    $time = strtotime($date);
    $week = date('N', $time);
    switch ($week){
        case '1':
            return "星期一";
            break;
        case '2':
            return "星期二";
            break;
        case '3':
            return "星期三";
            break;
        case '4':
            return "星期四";
            break;
        case '5':
            return "星期五";
            break;
        case '6':
            return "星期六";
            break;
        default:
            return "星期天";
    }
}

//生成类似uuid的随机字符串
function uuid($prefix = '')
{
    $chars = md5(uniqid(mt_rand(), true));
//    $uuid  = substr($chars,0,8) . '-';
//    $uuid .= substr($chars,8,4) . '-';
//    $uuid .= substr($chars,12,4) . '-';
//    $uuid .= substr($chars,16,4) . '-';
//    $uuid .= substr($chars,20,12);
    return $prefix . $chars;
}

/**
 *
 * execl数据导出
 * @param string $name 表头信息
 * @param string $title 模型名（如Member），用于导出生成文件名的前缀
 * @param array $cellName 表头及字段名
 * @param array $data 导出的表数据
 */
function exportExcel($name,$title,$cellName,$data)
{
    //引入核心文件
    vendor("PHPExcel.PHPExcel");
    $objPHPExcel = new \PHPExcel();
    //定义配置
    $topNumber = 2;//表头有几行占用
    $xlsTitle = iconv('utf-8', 'gb2312', $title);//文件名称
    $fileName = $title.date('_YmdHis');//文件名称
    $cellKey = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM',
        'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'
    );

    //写在处理的前面（了解表格基本知识，已测试）
//     $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);//所有单元格（行）默认高度
//     $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);//所有单元格（列）默认宽度
//     $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);//设置行高度
//     $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);//设置列宽度
//     $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);//设置文字大小
//     $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);//设置是否加粗
//     $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);// 设置文字颜色
//     $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置文字居左（HORIZONTAL_LEFT，默认值）中（HORIZONTAL_CENTER）右（HORIZONTAL_RIGHT）
//     $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
//     $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);//设置填充颜色
//     $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FF7F24');//设置填充颜色

    //处理表头标题
    $objPHPExcel->getActiveSheet()->mergeCells('A1:'.$cellKey[count($cellName)-1].'1');//合并单元格（如果要拆分单元格是需要先合并再拆分的，否则程序会报错）
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',$name);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    //处理表头
    foreach ($cellName as $k=>$v)
    {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellKey[$k].$topNumber, $v[1]);//设置表头数据
//        $objPHPExcel->getActiveSheet()->freezePane($cellKey[$k].($topNumber+1));//冻结窗口
        $objPHPExcel->getActiveSheet()->getStyle($cellKey[$k].$topNumber)->getFont()->setBold(true);//设置是否加粗
        $objPHPExcel->getActiveSheet()->getStyle($cellKey[$k].$topNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
        if($v[2] > 0)//大于0表示需要设置宽度
        {
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellKey[$k])->setWidth($v[2]);//设置列宽度
        }
    }
    //处理数据
    foreach ($data as $k=>$v)
    {
        foreach ($cellName as $k1=>$v1)
        {
            $objPHPExcel->getActiveSheet()->setCellValue($cellKey[$k1].($k+1+$topNumber), $v[$v1[0]]);
            if($v1[3] != "" && in_array($v1[3], array("LEFT","CENTER","RIGHT")))
            {
                $v1[3] = eval('return PHPExcel_Style_Alignment::HORIZONTAL_'.$v1[3].';');
                //这里也可以直接传常量定义的值，即left,center,right；小写的strtolower
                $objPHPExcel->getActiveSheet()->getStyle($cellKey[$k1].($k+1+$topNumber))->getAlignment()->setHorizontal($v1[3]);
            }
        }
    }
    //导出execl
    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
    header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $dir = ROOT_PATH . 'public' . DS . 'excel';
    if(!file_exists($dir)){
        //检查是否有该文件夹，如果没有就创建，并给予最高权限
        mkdir($dir, 0700,true);
    }
    $filePath = 'excel/'.$fileName.'.xls';
    $objWriter->save($filePath);
    return ['code'=>200,'msg'=>'/'.$filePath];
}

/**
 *数字金额转换成中文大写金额的函数
 *String Int  $num  要转换的小写数字或小写字符串
 *return 大写字母
 *小数位为两位
 **/
function get_amount($num){
    $c1 = "零壹贰叁肆伍陆柒捌玖";
    $c2 = "分角元拾佰仟万拾佰仟亿";
    $num = round($num, 2);
    $num = $num * 100;
    if (strlen($num) > 10) {
        return "数据太长，没有这么大的钱吧，检查下";
    }
    $i = 0;
    $c = "";
    while (1) {
        if ($i == 0) {
            $n = substr($num, strlen($num)-1, 1);
        } else {
            $n = $num % 10;
        }
        $p1 = substr($c1, 3 * $n, 3);
        $p2 = substr($c2, 3 * $i, 3);
        if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
            $c = $p1 . $p2 . $c;
        } else {
            $c = $p1 . $c;
        }
        $i = $i + 1;
        $num = $num / 10;
        $num = (int)$num;
        if ($num == 0) {
            break;
        }
    }
    $j = 0;
    $slen = strlen($c);
    while ($j < $slen) {
        $m = substr($c, $j, 6);
        if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
            $left = substr($c, 0, $j);
            $right = substr($c, $j + 3);
            $c = $left . $right;
            $j = $j-3;
            $slen = $slen-3;
        }
        $j = $j + 3;
    }
    if (substr($c, strlen($c)-3, 3) == '零') {
        $c = substr($c, 0, strlen($c)-3);
    }
    if (empty($c)) {
        return "零元整";
    }else{
        return $c . "整";
    }
}

/**
 * 生成二维码图片
 * @param $content 二维码内容
 * @param $logo logo图片
 * @return string
 */
function Qrcode($content,$logo = ""){
    import('org.phpqrcode', EXTEND_PATH);
    $date = date('Ymd');
    $dir = ROOT_PATH . 'public' . DS . 'uploads/ercode/'.$date;
    $url =  http_type();
    if(!file_exists($dir)){
        //检查是否有该文件夹，如果没有就创建，并给予最高权限
        mkdir($dir, 0700,true);
    }
    $name = md5(time().uuid()).".png";
    $value = "$content";             //二维码内容
    $errorCorrectionLevel = 'M';    //容错级别L(QR_ECLEVEL_L，7%)、M(QR_ECLEVEL_M，15%)、Q(QR_ECLEVEL_Q，25%)、H(QR_ECLEVEL_H，30%)
    $matrixPointSize = 6;           //生成图片大小
    //组装图片路径
    $filepath= $dir.DS.$name;
    \QRcode::png($value,$filepath , $errorCorrectionLevel, $matrixPointSize, 2);
    $QR = $filepath;              //已经生成的原始二维码图片文件
    if ($logo && file_exists($logo)) {
        $QR = imagecreatefromstring(file_get_contents($QR));   		//目标图象连接资源。
        $logo = imagecreatefromstring(file_get_contents($logo));   	//源图象连接资源。
        $QR_width = imagesx($QR);			//二维码图片宽度
        $QR_height = imagesy($QR);			//二维码图片高度
        $logo_width = imagesx($logo);		//logo图片宽度
        $logo_height = imagesy($logo);		//logo图片高度
        $logo_qr_width = $QR_width / 4;   	//组合之后logo的宽度(占二维码的1/5)
        $scale = $logo_width/$logo_qr_width;   	//logo的宽度缩放比(本身宽度/组合后的宽度)
        $logo_qr_height = $logo_height/$scale;  //组合之后logo的高度
        $from_width = ($QR_width - $logo_qr_width) / 2;   //组合之后logo左上角所在坐标点

        //重新组合图片并调整大小
        /*
         *	imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
         */
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
        $newname = 'uploads/ercode/'.$date.DS.md5(time().uuid()).".png";
        imagepng($QR, ROOT_PATH . 'public' . DS .$newname);
        //输出图片
        unlink($filepath);
        imagedestroy($QR);
        imagedestroy($logo);
        return $url.DS.$newname;
    }else{
        $QR = imagecreatefromstring(file_get_contents($QR));
        //输出图片
        imagedestroy($QR);
        return $url."/uploads/ercode/".$date."/".$name;
    }
}

/**
 * 对象转数组
 * @param $array
 * @return array
 */
function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    } if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

/**
 * curl 请求
 * @param $url
 * @param string $data
 * @param string $method
 * @param string $header
 * @return mixed
 */
function https_request($url, $data = '', $method = 'GET', $header = '')
{
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    if ($header) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    } else {
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    }
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    if ($method == 'POST') {
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        if ($data != '') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        }
    }
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}


/**
 * 对查询结果集进行排序,支持多维数组
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort($list,$field, $sortby='asc') {
    if(is_array($list)){
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc':// 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ( $refer as $key=> $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

/**
 * 邮件发送函数
 * @param string $mailTo 收件人邮箱，多个用逗号连接
 * @param string $subject 邮件主题
 * @param string $body 邮件内容
 */
function sendMail($mailTo,$subject,$body,$type = 'HTML',$debug = false) {
    import('maile/smtp', EXTEND_PATH);
    $smtpserver     = config('smtp.server'); //SMTP服务器
    $smtpserverport = config('smtp.serverport'); //SMTP服务器端口
    $smtpusermail   = config('smtp.usermail'); //SMTP服务器的用户邮箱
    $smtpemailto    = $mailTo;//收件人
    $smtpuser       = config('smtp.smtpuser'); //SMTP服务器的用户帐号
    $smtppass       = config('smtp.pass'); //SMTP服务器的用户密码
    $mailsubject    = $subject; //邮件主题
    $mailsubject    = "=?UTF-8?B?" . base64_encode($mailsubject) . "?="; //防止乱码
    $mailbody       = $body; //邮件内容
//    $mailbody = "=?UTF-8?B?".base64_encode($mailbody)."?="; //防止乱码
    $mailtype       = $type; //邮件格式（HTML/TXT）,TXT为文本邮件. 139邮箱的短信提醒要设置为HTML才正常
    $smtp           = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug    = $debug; //是否显示发送的调试信息
    $sta = $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
    return $sta;
}

/**
 * @method 多维数组变成一维数组
 * @staticvar array $result_array
 * @param type $array
 * @return type $array
 * @author yanhuixian
 */
function arrToOne($multi) {
    $arr = array();
    foreach ($multi as $key => $val) {
        if( is_array($val) ) {
            $arr = array_merge($arr, arrToOne($val));
        } else {
            $arr[] = $val;
        }
    }
    return $arr;
}


/*
 * 判断当前域名http或https,组装域名
 */
function http_type(){
$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
return  $http_type . $_SERVER['HTTP_HOST'];
}

/*
 * base64格式图片转图片文件
 */
function base64_img($base64url,$bool = false)
{
    //匹配出图片的格式
    $base64url = str_replace (' ' , '+' , $base64url);
    if ( preg_match ('/^(data:\s*image\/(\w+);base64,)/' , $base64url , $result) ) {
        $type = $result[ 2 ];
        $new_file = ROOT_PATH . 'public' . DS . 'uploads/face/' . date ('Ymd' , time ()) . "/";
        if ( !file_exists ($new_file) ) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir ($new_file , 0700,true);
        }
        $new = md5 (time ().uuid());
        $new_file = $new_file . $new . ".{$type}";
        if ( file_put_contents ($new_file , base64_decode (str_replace ($result[ 1 ] , '' , $base64url))) ) {
            //压缩图片
            if($bool == true){
//                $new = md5 (time ().uuid());
                image_png_size_add ($new_file , $new_file);
                //删除未压缩前图片
//                unlink ($new_file);
            }
            $url = http_type();
            $file_name =  "/uploads/face/" . date ('Ymd' , time ()) . "/" . $new . ".{$type}";
            return ['code'=>200,'msg'=>$file_name];
        } else {
            return ['code'=>100,'msg'=>'图片不是base64格式！'];
        }
    }
}

/*
 * 关联数组转为索引数组
 */
function toIndexArr($arr){
    $i=0;
    foreach($arr as $key => $value){
        $newArr[$i] = $value;
        $i++;
    }
    return $newArr;
}

/**
 * 二维数组按某个字段排序
 * @param $arr 二维数组
 * @param $field 排序字段
 * @param string $direction 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
 * @return mixed
 */
function arrayToSort($arr,$field,$direction = "SORT_DESC"){
    $arrSort = [];
    foreach($arr AS $uniqid => $row){
        foreach($row AS $key=>$value){
            $arrSort[$key][$uniqid] = $value;
        }
    }
    if(!empty($arr)){
        array_multisort($arrSort[$field], constant($direction), $arr);
    }
    return $arr;
}

/*
 * 过滤敏感词
 */
function filter_words($word){
    //获取敏感数据
    $sensitive = cache('sensitive_word');
    if(!$sensitive){
        $sensitive = Db::name('word')->column ('word');
        $sensitive = array_combine($sensitive,array_fill(0,count($sensitive),'**'));
        cache('sensitive_word',$sensitive);
    }
    //过滤
    $content = strtr($word, $sensitive);
    return $content;
}

/*
 * 淘宝接口：根据ip获取所在城市名称
 */
function get_ip_area($ip){
    return '内网IP';
    // if($ip == '127.0.0.1'){
    //     return '内网IP';
    // }
    // $url = "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}";
    // $ret = https_request($url);
    // $arr = json_decode($ret,true);
    // if($arr['code'] == 1){
    //     return '';
    // }else{
    //     return $arr['data']['country'].$arr['data']['region'].$arr['data']['city'].$arr['data']['isp'];
    // }
}