<?php
use think\Env;
return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式(排错)
    'app_debug'              => true,
    // 应用Trace(排错)
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    //'default_filter' => ['strip_tags', 'htmlspecialchars'],
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'admin',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],


    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__CSS__' => '/static/admin/css',
        '__JS__'  => '/static/admin/js',
        '__IMG__' => '/static/admin/images',
        '__IMAGES__' => '/static/assets/images',
        '__NJS__'  => '/static/assets/js',
        '__LIBS__'  => '/static/assets/libs',
        '__MODULE__'  => '/static/assets/module',
    ],
    //默认成功跳转对应的模板文件
    'dispatch_success_tmpl'  => APP_PATH.'admin/view/public/success.tpl',
    //默认错误跳转对应的模板文件
    'dispatch_error_tmpl'    => APP_PATH.'admin/view/public/error.tpl',


    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',
    'http_exception_template'    =>  [
        // 定义404错误的重定向页面地址
        404 =>  APP_PATH.'404.html',
        //定义500错误的重定向页面地址
        500 =>  APP_PATH.'500.html'
    ],


    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],


    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],


    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache'                  => [

        // 选择模式
        'type'   => 'complex',
        // 默认(文件缓存)
        'default'=>[
            // 驱动方式
            'type'   => 'File',
            // 缓存保存目录
            'path'   => CACHE_PATH,
            // 缓存前缀
            'prefix' => '',
            // 缓存有效期 0表示永久缓存
            'expire' => 0,
        ],
        //redis缓存设置
        'redis'   =>  [
            // 驱动方式
            'type'   => 'redis',
            // 服务器地址
            'host'   => '127.0.0.1',  //redis服务器ip
            'port'   => '6379',
        ],
    ],



    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
//        'use_trans_sid'=>1,
//        'use_only_cookies'=>0
    ],


    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],
    //分页配置
    'paginate'               => [
        'type'      => 'page',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],

    'sql_explain' => false,     // 是否需要进行SQL性能分析
    'extra_config_list' => ['database', 'route', 'validate'],//各模块公用配置


    // +----------------------------------------------------------------------
    // | auth配置
    // +----------------------------------------------------------------------
    'auth_config'  => [
        'auth_on'           => 1, // 权限开关
        'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        => 'jy_auth_group', // 用户组数据不带前缀表名
        'auth_group_access' => 'jy_auth_group_access', // 用户-用户组关系不带前缀表
        'auth_rule'         => 'jy_auth_rule', // 权限规则不带前缀表
        'auth_user'         => 'jy_admin', // 用户信息不带前缀表
    ],

    // +----------------------------------------------------------------------
    // | 跳过Auth权限验证
    // +----------------------------------------------------------------------
    'auth_pass' =>[
        'admin/index/index',
        'admin/index/indexpage',
        'admin/user/adminedit',
        'admin/upload/updateface',//修改管理员头像
        'admin/user/editpwd',//修改管理员密码
        'admin/base/place',//三级联动
        'admin/user/checkname',//检验名字
        'admin/role/checkrole',//检验角色
        'admin/upload/upload',//上传图片七牛
        'admin/upload/deleteimg',//删除七牛图片
        'admin/upload/uploadOnYpy',//上传图片到又拍云
        'admin/upload/deleteYpy',//删除又拍云图片
        'admin/upload/layUpload',//layui上传图片
        'admin/upload/layUploadVideo',//layui上传视频
        'admin/upload/wangUpload',//wangEditor图片上传
        'admin/upload/ueditorUpload',//百度富文本上传图片至第三方CDN接口
        'admin/upload/uploadLocal',//图片上传至本地
        'admin/upload/deleteLocal',//删除本地图片
    ],


    // +----------------------------------------------------------------------
    // | 数据库备份设置
    // +----------------------------------------------------------------------
    'data_backup_path'     => '../data/',   //数据库备份路径必须以 / 结尾；
    'data_backup_part_size' => '20971520',  //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
    'data_backup_compress' => '0',          //压缩备份文件需要PHP环境支持gzopen,gzwrite函数 0:不压缩 1:启用压缩
    'data_backup_compress_level' => '9',    //压缩级别   1:普通   4:一般   9:最高


    // +----------------------------------------------------------------------
    // | 极验验证,请到官网申请ID和KEY，http://www.geetest.com/
    // +----------------------------------------------------------------------
    'verify_type'  => '1', //验证码类型：0关闭，1数字or中文验证码，2极验验证，
    'auth_key'     => 'KjTG6jjovVqUdJYpXNxiMpfaejbDEBUnTzDcrEcN', //默认数据加密KEY
    'pages'        => '10', //默认分页数
    'salt'         => 'wZPb~yxvA!ir38&Z', //加密串
    'quality'      => '90', //默认压缩图片的质量
    'period'       => '7200', //默认2小时未操作重新登录 单位秒
    'log_std'      => '1', //1开启本地日志文件 0关闭本地日志
    //极验配置
    'gee'             => [
        'gee_id'           => '',//极验id
        'gee_key'          => '',//极验key
    ],
    //极验配置
    'smtp'            => [
        'server'           => "",//SMTP服务器 例如：smtp.qq.com/smtp.163.com
        'serverport'       => "",//SMTP服务器端口 例如：25
        'usermail'         => "",//SMTP服务器的用户邮箱
        'pass'             => "",//SMTP服务器的用户密码
        'smtpuser'         => "" //SMTP服务器的用户帐号
    ],
    //七牛配置
    'qiniu'           => [
        'accessKey'        => '',//accessKey
        'secretKey'        => '',//secretKey
        'bucket'           => '',//空间名
        'pipeline'         => '',//多媒体队列
        'domain'           => '',//访问域名
    ],
    //云之讯配置
    'yzx'             => [
        'appid'            => '',//应用的ID
        'templateid'       => '',//模板ID
        'accountsid'       => '',//Account Sid
        'token'            => '',//Auth Token
    ],
    //阿里大于配置
    'alidy'           => [
        'AccessKeyId'      => '',// AccessKeyId
        'AccessKeySecret'  => '',// AccessKeySecret
        'SignName'         => "",//签名
        'TemplateCode'     => ''//模板id
    ],
    //极光推送配置
    'jpush'           => [
        'appKey'           => '',//appKey
        'masterSecret'     => ''//masterSecret
    ],
    //又拍云配置
    'upyun'           => [
        'serviceName'      => '',//服务名称
        'operatorName'     => '',//操作员
        'operatorPassword' => '',//密码
        'domain'           => '',//访问域名
    ],
    //socket
    'socket'          => [
        'socket_name'=>'websocket://0.0.0.0:2358'
    ]
];