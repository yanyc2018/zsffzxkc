<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/vip/timepart.html";i:1606658626;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>表格缩略图</title>
    <link rel="stylesheet" href="/static/assets/libs/layui/css/layui.css"/>
    <link rel="stylesheet" href="/static/assets/module/admin.css?v=317"/>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        #tbImgTable + .layui-table-view .layui-table-body tbody > tr > td {
            padding: 0;
        }

        #tbImgTable + .layui-table-view .layui-table-body tbody > tr > td > .layui-table-cell {
            height: 48px;
            line-height: 48px;
        }

        .tb-img-circle {
            width: 40px;
            height: 40px;
            cursor: zoom-in;
            border-radius: 50%;
            border: 2px solid #dddddd;
        }
    </style>
</head>
<body>
<!-- 加载动画 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <ul class="layui-tab-title" style="margin-bottom: 20px;">
                <li class="layui-this"><a href="javascript:location.reload();">会员周期管理</a></li>
                <li><a ew-href="<?php echo url('timepartadd'); ?>" ew-title="添加会员周期">添加会员周期</a></li>
                
            </ul>
            <!-- 表格工具栏 -->
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label w-auto">搜索:</label>
                        <div class="layui-input-inline">
                            <input name="keyword" class="layui-input" placeholder="输入关键字"/>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn icon-btn" lay-filter="imgTbSearch" lay-submit>
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>                    
                    </div>
                </div>
            </div>
            <!-- 数据表格 -->
            <table id="tbImgTable" lay-filter="tbImgTable"></table>
        </div>
    </div>
</div>
<!-- 表格操作列 -->
<script type="text/html" id="tbBasicTbBar">
    <a  href="../table/agentThere.html" class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="edit">删除</a>
   
</script>

<script type="text/javascript" src="/static/assets/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/assets/js/common.js?v=317"></script>
<script>
    layui.use(['layer',  'form', 'table', 'util', 'dropdown'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var table = layui.table;

        /* 渲染表格 */
        table.render({
            elem: '#tbImgTable',
            url: "<?php echo url('admin/vip/timepart'); ?>",
            page: true,
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: '编号', align: 'center',sort: true},
                {field: 'name', title: '会员周期名称', align: 'center',sort: true},
                {field: 'price', title: '价格(元)', align: 'center', sort: true},
                {field: 'bfprice', title: '原价(元)', align: 'center' ,sort: true},
                {field: 'zsscore', title: '赠送积分', align: 'center', sort: true},
                {title: '操作', toolbar: '#tbBasicTbBar',align: 'center', minWidth:200}
            ]]
        });

    
    });
</script>
</body>
</html>
