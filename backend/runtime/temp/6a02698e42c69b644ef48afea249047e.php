<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/course/index.html";i:1606658626;}*/ ?>
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
            width: 400px;
            /*height: 40px;*/
            cursor: zoom-in;
            /*border-radius: 50%;*/
            /*border: 2px solid #dddddd;*/
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
                <li class="layui-this"><a href="javascript:location.reload();">课程管理</a></li>
                <li><a ew-href="<?php echo url('add_menu'); ?>" ew-title="添加目录">添加目录</a></li>
                <li><a ew-href="<?php echo url('add_course'); ?>" ew-title="添加课程">添加课程</a></li>
<!--                <li><a ew-href="<?php echo url('menu_orderlist'); ?>" ew-title="单目录购买记录">购买记录</a></li>-->
            </ul>
            <!-- 表格工具栏 -->
            <form class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label w-auto">课程名称:</label>
                        <div class="layui-input-inline">
                            <input name="keyword" class="layui-input" placeholder="输入关键字"/>
                        </div>
                    </div>
                    <?php if(!$media || $media==''): ?>
                    <div class="layui-inline">
                        <label class="layui-form-label">媒体类型:</label>
                        <div class="layui-input-inline">
                            <select name="mediatype">
                                <option value="">请选择媒体类型</option>
                                <option value="">全部</option>
                                <option value="video">视频</option>
                                <option value="audio">音频</option>
                                <option value="tuwen">图文</option>
                                <option value="all">不限</option>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="layui-inline">
                        <button class="layui-btn icon-btn" lay-filter="courseTbSearch" lay-submit>
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>
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
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="add_course">添加课程</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" data-dropdown="#userTbDrop{{d.LAY_INDEX}}" no-shade="true">
        更多<i class="layui-icon layui-icon-drop" style="font-size: 12px;margin-right: 0;"></i></a>
    <!-- 下拉菜单 -->
    <ul class="dropdown-menu-nav dropdown-bottom-right layui-hide" id="userTbDrop{{d.LAY_INDEX}}">
        <div class="dropdown-anchor"></div>
        <li><a lay-event="view"><i class="layui-icon layui-icon-more-vertical"></i>查看课程</a></li>
        <li><a lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除目录</a></li>
<!--        <li><a lay-event="lock"><i class="layui-icon layui-icon-password"></i>添加课程</a></li>-->
    </ul>
</script>
<!-- 表格状态列 -->
<script type="text/html" id="userTbState">
    <input type="checkbox" lay-filter="userTbStateCk" value="{{d.id}}" lay-skin="switch"
           lay-text="上架|下架" {{d.status==1?'checked':''}} style="display: none;"/>
    <p style="display: none;">{{d.status==1?'上架':'下架'}}</p>
</script>
<script type="text/html" id="tj">
    <input type="checkbox" lay-filter="tj_set" value="{{d.id}}" lay-skin="switch"
           lay-text="推荐|不推荐" {{d.is_tj==1?'checked':''}} style="display: none;"/>
    <p style="display: none;">{{d.is_tj==1?'推荐':'不推荐'}}</p>
</script>
<script type="text/javascript" src="/static/assets/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/assets/js/common.js?v=317"></script>
<script>
    layui.use(['layer',  'form', 'table', 'util', 'dropdown','index'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var table = layui.table;
        var index = layui.index;
        var form = layui.form;
        /* 渲染表格 */
        var insTb=table.render({
            elem: '#tbImgTable',
            url: "<?php echo url('admin/course/index'); ?>?media="+"<?php echo $media; ?>",
            page: true,
            cellMinWidth: 100,
            cols: [[
                {field: 'id', title: '编号', align: 'center',width:100,sort: true},
                {field: 'xh', title: '排序', align: 'center',width:100,sort: true},
                {field: 'media_name', title: '媒体', align: 'center',width:100},
                {field: 'menuname', title: '目录名称', align: 'center'},
                {
                    title: '封面', templet: function (d) {
                        var url = d.thumb || '../../../assets/images/head.jpg';
                        return '<img src="' + url + '" class="tb-img-circle" tb-img alt=""/>';
                    }, align: 'center', unresize: true,width:200
                },
                {field: 'classify', title: '所属分类', align: 'center',width:200},
                {field: 'teacher', title: '讲师', align: 'center', width:150},
                {field: 'price', title: '价格', align: 'center',width:150 ,sort: true},
                {field: 'status', title: '状态', templet: '#userTbState', width: 100},
                {field: 'is_tj', title: '推荐', templet: '#tj', width: 100},
                {title: '操作', toolbar: '#tbBasicTbBar',align: 'center', minWidth:300}
            ]]
        });
        /* 表格搜索 */
        form.on('submit(courseTbSearch)', function (data) {
            insTb.reload({where: data.field, page: {curr: 1}});
            return false;
        });
        // 工具条点击事件
        table.on('tool(tbImgTable)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            if (layEvent == 'edit') {
                index.openTab({
                    title: '编辑目录',
                    url: "<?php echo url('add_menu'); ?>?id="+data.id,
                });
            } else if (layEvent == 'add_course') {
                index.openTab({
                    title: '添加课程',
                    url: "<?php echo url('add_course'); ?>?menuid="+data.id,
                });
            }else if (layEvent == 'del') {
                doDel(data.id);
            }else if (layEvent == 'view') {
                index.openTab({
                    title: '子课程列表',
                    url: "<?php echo url('course_list'); ?>?menuid="+data.id,
                });
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
                $.post("<?php echo url('menu_del'); ?>", {
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
                type:'menu',
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
        form.on('switch(tj_set)', function (obj) {
            var loadIndex = layer.load(2);
            $.get("<?php echo url('tj_switch'); ?>", {
                id: obj.elem.value,
                type:'menu',
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

    });
</script>
</body>
</html>
