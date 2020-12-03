<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/index.html";i:1606658626;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="/static/assets/images/favicon.ico" rel="icon">
    <title><?php echo $title['value']; ?></title>
    <link rel="stylesheet" href="/static/assets/libs/layui/css/layui.css"/>
    <link rel="stylesheet" href="/static/assets/module/admin.css?v=317"/>
    <link rel="stylesheet" href="/static/admin/css/font-awesome.min.css"><!--fontAwesome图标库-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <!-- 头部 -->
    <div class="layui-header">
        <div class="layui-logo">
            <img src="/static/assets/images/logo.png"/>
            <cite><?php echo $title['value']; ?></cite>
        </div>
        <ul class="layui-nav layui-layout-left">
             <li class="layui-nav-item" lay-unselect>
                <a ew-event="flexible" title="侧边伸缩"><i class="layui-icon layui-icon-shrink-right"></i></a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="refresh" title="刷新"><i class="layui-icon layui-icon-refresh-3"></i></a>
            </li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="message" title="消息">
                    <i class="layui-icon layui-icon-notice"></i>
                    <span class="layui-badge-dot"></span>
                </a>
            </li>
<!--            <li class="layui-nav-item" lay-unselect>-->
<!--                <a ew-event="note" title="便签"><i class="layui-icon layui-icon-note"></i></a>-->
<!--            </li>-->
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="clear" title="清除缓存"><i class="layui-icon layui-icon-delete"></i></a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a ew-event="fullScreen" title="全屏"><i class="layui-icon layui-icon-screen-full"></i></a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a ew-event="lockScreen" title="锁屏"><i class="layui-icon layui-icon-password"></i></a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a>
                    <img src="/static/assets/images/head.jpg" class="layui-nav-img">
                    <cite><?php echo $admin_name; ?></cite>
                </a>
                <dl class="layui-nav-child">
                    <dd lay-unselect><a ew-href="page/template/user-info.html">个人中心</a></dd>
                    <dd lay-unselect><a ew-event="psw">修改密码</a></dd>
                    <hr>
                    <dd lay-unselect><a ew-event="logout" data-url="<?php echo url('admin/login/loginOut'); ?>">退出</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a ew-event="theme" data-url="<?php echo url('admin/Index/theme'); ?>" title="主题"><i class="layui-icon layui-icon-more-vertical"></i></a>
            </li>
        </ul>
    </div>

    <div class="layui-side">
        <div class="layui-side-scroll">
            <ul class="layui-nav layui-nav-tree" lay-filter="admin-side-nav" lay-shrink="_all">
                <?php if(!empty($menu)): if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): if( count($menu)==0 ) : echo "" ;else: foreach($menu as $key=>$vo): ?>
                <li class="layui-nav-item">
                    <?php if($vo['name'] == '#'): ?>
                    <a href="javascript:;"><i class="<?php echo $vo['css']; ?> layui-font"></i>&emsp;<cite><?php echo $vo['title']; ?></cite></a>
                    <?php else: ?>
                    <a lay-href="<?php echo $vo['href']; ?>"><i class="<?php echo $vo['css']; ?> layui-font"></i>&emsp;<cite><?php echo $vo['title']; ?></cite></a>
                    <?php endif; if($vo['name'] == '#'): ?>
                    <dl class="layui-nav-child">
                        <?php if(!empty($vo['child'])): if(is_array($vo['child']) || $vo['child'] instanceof \think\Collection || $vo['child'] instanceof \think\Paginator): if( count($vo['child'])==0 ) : echo "" ;else: foreach($vo['child'] as $key=>$v): if($v['name'] != '##'): ?>
                        <dd data-name="console">
                            <a lay-href="<?php echo $v['href']; ?>"><?php echo $v['title']; ?></a>
                        </dd>
                        <?php else: ?>
                        <dd data-name="grid">
                            <a href="javascript:;"><?php echo $v['title']; ?><span class="layui-nav-more"></span></a>
                            <dl class="layui-nav-child">
                                <?php if(!empty($v['child'])): if(is_array($v['child']) || $v['child'] instanceof \think\Collection || $v['child'] instanceof \think\Paginator): if( count($v['child'])==0 ) : echo "" ;else: foreach($v['child'] as $key=>$z): ?>
                                <dd data-name="list"><a lay-href="<?php echo $z['href']; ?>"><?php echo $z['title']; ?></a></dd>
                                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                            </dl>
                        </dd>
                        <?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
                    </dl>
                    <?php endif; ?>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </ul>
        </div>
    </div>
    <!-- 主体部分 -->
    <div class="layui-body"></div>
    <!-- 底部 -->
    <div class="layui-footer layui-text">
        <?php echo $copyright['value']; ?>
        <span class="pull-right">Version <?php echo $version['value']; ?></span>
    </div>
</div>

<!-- 加载动画 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>

<!-- js部分 -->
<script type="text/javascript" src="/static/assets/libs/jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/static/assets/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/assets/js/common.js?v=317"></script>
<script>

    layui.use(['index'], function () {
        var $ = layui.jquery;
        var index = layui.index;

        // 默认加载主页
        index.loadHome({
            menuPath: "<?php echo url('admin/Index/indexPage'); ?>",
            menuName: '<i class="layui-icon layui-icon-home"></i>'
        });

    });
    // $(".page li").click(function(){
    //     $(".page li a").css({"background-color":"#191a23"})
    //     $(this).children().css({"background-color":"#009688"})
    //     $(this).siblings().children().css({"background-color":"#191a23"})
    // })

</script>
</body>
</html>