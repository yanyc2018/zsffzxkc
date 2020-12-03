<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/shop/notice.html";i:1606658624;}*/ ?>
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
            <!-- 表格工具栏 -->
            <form class="layui-form toolbar">
                <div class="layui-form toolbar">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label w-auto">标题:</label>
                            <div class="layui-input-inline">
                                <input name="keyword" class="layui-input" placeholder="输入关键字"/>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn icon-btn" lay-filter="imgTbSearch" lay-submit>
                                <i class="layui-icon">&#xe615;</i>搜索
                            </button>
                            <a ew-href="<?php echo url('notice_add'); ?>" ew-title="新增公告">
                                <button class="layui-btn icon-btn" type="button">
                                    <i class="layui-icon">&#xe654;</i>新增
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
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

<script type="text/javascript" src="/static/assets/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/assets/js/common.js?v=317"></script>
<script>
    layui.use(['layer', 'table','index'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var table = layui.table;
        var index = layui.index;
        var form = layui.form;
        /* 渲染表格 */
        var insTb=table.render({
            elem: '#tbImgTable',
            url: "<?php echo url('admin/shop/notice'); ?>",
            page: true,
            cellMinWidth: 100,
            limits: [10, 20, 30, 40, 50],
            limit: "<?php echo config('pages'); ?>",
            cols: [[
                {field: 'id', title: '编号', align: 'center',width:100,sort: true},
                {field: 'title', title: '标题', align: 'center', sort: true},
                {field: 'name', title: '内容', align: 'center', sort: true},
                {field: 'addtime', title: '发布时间', align: 'center', sort: true},
                {field: 'src', title: '小程序链接', align: 'center', sort: true},
                {field: 'gzh_src', title: '公众号链接', align: 'center', sort: true},
                {title: '操作', toolbar: '#tbBasicTbBar',align: 'center', minWidth: 200}
            ]]
        });
        // 工具条点击事件
        table.on('tool(tbImgTable)', function (obj) {
            var data = obj.data;

            var layEvent = obj.event;
            if (layEvent == 'edit') {
                index.openTab({
                    title: '编辑公告',
                    url: "<?php echo url('notice_add'); ?>?id="+data.id,
                });
            } else if (layEvent == 'del') {
                doDel(data.id);
            }
        });
        // 删除
        function doDel(id) {
            layer.confirm('确定要删除吗？', {
                shade: .1,
                skin: 'layui-layer-admin'
            }, function (i) {
                layer.close(i);
                layer.load(2);
                $.post("<?php echo url('notice_del'); ?>", {
                    id: id
                }, function (res) {
                    layer.closeAll('loading');
                    if (res.code == 0) {
                        layer.msg(res.msg, {icon: 1});
                        insTb.reload({page: {curr: 1}});
                    } else {
                        layer.msg(res.msg, {icon: 2});
                    }
                }, 'json');
            });
        }

        /* 修改状态 */
        form.on('switch(userTbStateCk)', function (obj) {
            var loadIndex = layer.load(2);
            $.get("<?php echo url('status_switch'); ?>", {
                id: obj.elem.value,
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
        /* 点击图片放大 */
        $(document).off('click.tbImg').on('click.tbImg', '[tb-img]', function () {
            layer.photos({photos: {data: [{src: $(this).attr('src')}]}, shade: .1, closeBtn: true});
        });
        /* 表格搜索 */
        form.on('submit(imgTbSearch)', function (data) {
            insTb.reload({where: data.field, page: {curr: 1}});
            return false;
        });
    });
</script>
</body>
</html>
