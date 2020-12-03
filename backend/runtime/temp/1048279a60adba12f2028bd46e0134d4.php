<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/plugin/credit.html";i:1606658624;}*/ ?>
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
                <li class="layui-this"><a href="javascript:location.reload();">积分商城管理</a></li>
                <li><a ew-href="<?php echo url('creditadd'); ?>" ew-title="添加积分商品">添加积分商品</a></li>
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
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
   
</script>
<script type="text/html" id="userTbState">
    <input type="checkbox" lay-filter="userTbStateCk" value="{{d.id}}" lay-skin="switch"
           lay-text="显示|隐藏" {{d.status==1?'checked':''}} style="display: none;"/>
    <p style="display: none;">{{d.status==1?'显示':'隐藏'}}</p>
</script>

<script type="text/javascript" src="/static/assets/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/assets/js/common.js?v=317"></script>
<script>
    layui.use(['layer',  'form', 'table', 'util', 'dropdown','index','admin'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var table = layui.table;
        var index = layui.index;
        var admin = layui.admin;
        var form = layui.form;
        /* 渲染表格 */
        table.render({
            elem: '#tbImgTable',
            url: "<?php echo url('admin/plugin/credit'); ?>",
            page: true,
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: '编号', align: 'center',sort: true},
                {field: 'goodsname', title: '商品名称', align: 'center' ,sort: true},
                {field: 'goodstype', title: '商品类型', align: 'center' ,sort: true},
                {field: 'goodsid', title: '商品ID', align: 'center', sort: true},
                {field: 'credit', title: '所需积分', align: 'center',sort: true},
                {field: 'stock', title: '库存', align: 'center', sort: true},
                {field: 'addtime', title: '添加时间', align: 'center' ,sort: true},
                {field: 'status', title: '状态', templet: '#userTbState', width: 100},
                {title: '操作', toolbar: '#tbBasicTbBar',align: 'center', minWidth:300}
            ]]
        });
        // 工具条点击事件
        table.on('tool(tbImgTable)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            if (layEvent == 'view') {
                index.openTab({
                    title: '查看本批次优惠码',
                    url: "<?php echo url('yhmlist'); ?>?piciid="+data.id,
                });
            } else if (layEvent == 'fenpei') {
                index.openTab({
                    title: '分配优惠码',
                    url: "<?php echo url('jhmfp'); ?>?id="+data.id+"&jhmtype=yhm",
                });
            }else if (layEvent == 'del') {
                doDel(data.id);
            }else if (layEvent == 'daochu') {
                index.openTab({
                    title: '优惠码导出',
                    url: "<?php echo url('jhm_daochu'); ?>?id="+data.id+"&jhmtype=yhm",
                });
            }
        });
        /* 修改状态 */
        form.on('switch(userTbStateCk)', function (obj) {
            var loadIndex = layer.load(2);
            $.get("<?php echo url('status_switch'); ?>", {
                id: obj.elem.value,
                type:'credit',
                status: obj.elem.checked ? 1 : 0
            }, function (res) {
                layer.close(loadIndex);
                if (res.code === 200) {
                    layer.msg(res.msg, {icon: 1,time:1500});
                } else {
                    layer.msg(res.msg, {icon: 2});
                    $(obj.elem).prop('checked', !obj.elem.checked);
                    form.render('checkbox');
                }
            }, 'json');
        });
    });
</script>
</body>
</html>
