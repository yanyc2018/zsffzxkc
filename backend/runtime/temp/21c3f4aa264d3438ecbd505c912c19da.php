<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:82:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/role/index.html";i:1606658624;s:75:"/www/wwwroot/test1.sxjiangyan.com/application/admin/view/public/header.html";i:1606658628;s:75:"/www/wwwroot/test1.sxjiangyan.com/application/admin/view/public/footer.html";i:1606658628;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo config('WEB_SITE_TITLE'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/admin/js/layui/css/layui.css" >
    <link rel="stylesheet" href="/static/admin/css/admin.css" >
    <link rel="stylesheet" href="/static/admin/css/plugins/viewer/viewer.css"><!--viewer图片查看器-->
    <link rel="stylesheet" href="/static/admin/css/font-awesome.min.css"><!--fontAwesome图标库-->
    <link rel="stylesheet" href="/static/admin/css/plugins/cropper/ImgCropping.css" ><!--图片裁剪组件-->
    <link rel="stylesheet" href="/static/admin/css/plugins/cropper/cropper.min.css" ><!--图片裁剪组件-->
    <!--<link rel="stylesheet" href="/static/admin/css/plugins/animate/animate.min.css" >-->
    <link rel="stylesheet" href="/static/admin/js/plugins/zTree/zTreeStyle.css" ><!--zTree组件-->
    <link rel="stylesheet" href="/static/admin/css/plugins/formSelects/formSelects-v4.css" ><!--select多选组件-->
    <link rel="stylesheet" href="/static/admin/js/plugins/webuploader/webuploader.css" ><!--webUploader上传组件-->
    <link rel="stylesheet" href="/static/admin/js/plugins/webuploader/style.css" ><!--webUploader上传组件-->
    <link rel="stylesheet" href="/static/admin/js/plugins/wx-audio/wx-audio.css" ><!--音频播放器组件-->
    <link rel="stylesheet" href="/static/admin/css/plugins/toastr/toastr.css" ><!--toastr通知组件-->
    <link rel="stylesheet" href="/static/assets/module/admin.css?v=317"/>
    <style>
        /*layui滚动条自适应*/
        /*.layui-body{overflow-y: scroll;}*/
        /*body{overflow-y: scroll;}*/
        /*灯箱图片*/
        /*.closeP{width:60px;height:60px;text-align: center;line-height: 70px;border-radius:30px;background:rgba(0,0,0,0.5);font-size: 25px;position: fixed;top:-23px;right:-20px;color: #ccc;cursor: pointer;}*/
        /*.cha{position:relative;top:1px;right:8px;}*/
        /*.closeP:hover{color: white}*/
        /*.showP{width: 100%;height: 100vh;background: rgba(0,0,0,0.5);text-align: center;position: fixed;top: 0;left: 0;z-index: 1000;}*/
        /*图标*/
        /*#chooseicon {margin:20px;}*/
        /*#chooseicon ul { margin:5px 0 0 0;}*/
        /*#chooseicon ul li{width:41px;height:41px;line-height:41px;border:1px solid #e7e7e7;padding:1px;margin:1px;text-align: center;font-size:18px;float: left;}*/
        /*#chooseicon ul li:hover{border:1px solid #2c3e50;cursor:pointer;}*/

        /* 输入框添加蓝色边框效果，阴影边框效果  */
        /*.layui-input:focus,*/
        /*.layui-textarea:focus {*/
            /*border-color: rgba(91, 192, 222, 0.8) !important;*/
            /*-webkit-box-shadow: 0 0 5px rgba(91, 192, 222, .5);*/
            /*-moz-box-shadow: 0 0 5px rgba(91, 192, 222, .5);*/
            /*box-shadow: 0 0 5px rgba(91, 192, 222, .5);*/
        /*}*/

        /*.layui-input:hover,*/
        /*.layui-textarea:hover {*/
            /*border-color: rgba(91, 192, 222, 0.8) !important;*/
            /*-webkit-box-shadow: 0 0 5px rgba(91, 192, 222, .5);*/
            /*-moz-box-shadow: 0 0 5px rgba(91, 192, 222, .5);*/
            /*box-shadow: 0 0 5px rgba(91, 192, 222, .5);*/
        /*}*/

        /*!* 表单验证失败红色边框效果，阴影效果 *!*/
        /*.layui-form-danger, .layui-form-danger:focus, .layui-form-danger:hover{*/
            /*border-color: rgba(255,87,34, .8) !important;*/
            /*-webkit-box-shadow: 0 0 5px rgba(255,87,34, .5);*/
            /*-moz-box-shadow: 0 0 5px rgba(255,87,34, .5);*/
            /*box-shadow: 0 0 5px rgba(255,87,34, .5);*/
        /*}*/
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="start" class="layui-input" onfocus="wk.lay_date(this);" placeholder="创建开始日期">
                    </div>
                    <div class="layui-form-mid">
                        -
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="end" class="layui-input" onfocus="wk.lay_date(this);" placeholder="创建结束日期">
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" id="key" class="layui-input" name="key" value="" placeholder="输入需查询的角色名"/>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <button class="layui-btn" lay-submit="" lay-filter="LAY-search">立即搜索</button>
                        <button  class="layui-btn layui-btn-normal" id="empty"  lay-submit="" lay-filter="LAY-search">重置</button>
                    </div>
                </div>
            </div>
            <div>
                <button class="layui-btn" data-type="add" onclick="wk.layer_show('添加角色','<?php echo url('roleAdd'); ?>')">
                    <i class="fa fa-plus"></i> 添加角色
                </button>
                <span class="layui-btn-dropdown" style="display:inline-block;">
                    <button class="layui-btn layui-btn-danger" data-toggle="dropdown"><i class="fa fa-wrench"></i> 批量操作 <i class="fa fa-caret-down"></i></button>
                    <ul class="layui-dropdown-menu layui-anim layui-anim-upbit">
                        <li><a href="javascript:;" class="layuiBtn" data-type="getCheckData"><i class="fa fa-trash-o"></i> 批量删除 </a></li>
                            <li><a  href="javascript:;" class="layuiBtn" data-type="usingAll" id="onAll"><i class="fa fa-check-circle"></i> 批量启用</a></li>
                            <li><a  href="javascript:;" class="layuiBtn" data-type="forbiddenAll" id="offAll"><i class="fa fa-ban"></i> 批量禁用</a></li>
                    </ul>
                </span>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="LAY-table-manage" lay-filter="LAY-table-manage"></table>
            <!--操作模板-->
            <script type="text/html" id="opeBar">
                <a class="layui-btn layui-btn-xs" title="编辑" onclick="wk.layer_show('编辑角色','<?php echo url('roleEdit'); ?>?id={{d.id}}')"><i class="fa fa-pencil"></i></a>
                {{# if(d.id!==1){ }}
                    <a class="layui-btn layui-btn-info layui-btn-xs" title="权限分配" onclick="giveQx({{d.id}})"><i class="fa fa-unlock"></i></a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" title="删除" onclick="wk.confirm('{{d.id}}','<?php echo url('roleDel'); ?>')"><i class="fa fa-trash-o"></i></a>
                {{# } }}
            </script>
            <!--状态模板-->
            <script type="text/html" id="staBar">
                {{# if(d.id!==1){ }}
                    <input type="checkbox" value="{{d.id}}"  lay-skin="switch"  lay-text="ON|OFF" lay-filter="OnOff"{{ d.status == 1 ? 'checked' : '' }} >
                {{# } }}
            </script>
        </div>
    </div>
</div>
<!-- 权限分配 -->
<div class="zTreeDemoBackground left" style="display: none" id="role">
    <input type="hidden" id="nodeid">
    <div class="layui-form-item layui-col-xs-offset3 layui-col-xs5">
        <ul id="treeType" class="ztree"></ul>
    </div>
    <div class="layui-form-item layui-col-xs-offset3 layui-col-xs2">
        <input value="确认分配" class="layui-btn" id="postform"/>
    </div>
</div>
<div id="headCrop" style="display:none">
    <div class="tailoring-content-one">
        <label title="选择图片" for="chooseImg" class="layui-btn">
            <input type="file" accept="image/jpg,image/jpeg,image/png" name="file" id="chooseImg" class="hidden" onchange="selectImg(this)"><i class="fa fa-cloud-upload"></i>
            选择图片
        </label>
    </div>
    <div class="ibox-content">
        <div class="tailoring-content">
            <div class="tailoring-content-two">
                <div class="tailoring-box-parcel" style="text-align: center">
                    <img id="tailoringImg">
                    <span class="word" style="position:relative;top:50%;font-size:14px;color: #c2c2c2">仅支持JPG、JPEG、PNG格式的图片文件</span><br>
                    <!--<span class="size" style="position:relative;top:50%;font-size:16px">文件不能大于2MB</span>-->
                </div>
                <div class="preview-box-parcel">
                    <!--<p>图片预览：</p>-->
                    <div class="square previewImg"></div>
                    <div class="circular previewImg"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <span class="layui-btn cropper-reset-btn" style="float:left">复位</span>
        <span class="layui-btn zoomIn" style="float:left">放大</span>
        <span class="layui-btn zoomOut" style="float:left">缩小</span>
        <span class="layui-btn cropper-rotate-btn" style="float:left">旋转</span>
        <span class="layui-btn cropper-scaleX-btn" style="float:left">换向</span>
        <span class="layui-btn " id="sureCut"><i class="fa fa-save"></i> 保存</span>
        <span class="layui-btn layui-btn-primary" onclick="layer.closeAll()"><i class="fa fa-close"></i> 关闭</span>
    </div>
</div>
<script src="/static/admin/js/jquery.min.js"></script>
<script src="/static/admin/js/layui/layui.js"></script>
<script src="/static/admin/js/plugins/viewer/viewer.js"></script><!--viewer图片查看器-->
<script src="/static/admin/js/Icon.js"></script><!--fontAwesome图标库-->
<script src="/static/admin/js/wk.js"></script><!--封装方法-->
<script src="/static/admin/js/common.js"></script><!--全局监听ajax-->
<script src="/static/admin/js/plugins/cropper/cropper.min.js"></script><!--图片裁剪组件-->
<script src="/static/admin/js/plugins/zTree/jquery.ztree.core-3.5.js"></script><!--zTree组件-->
<script src="/static/admin/js/plugins/zTree/jquery.ztree.excheck-3.5.js"></script><!--zTree选择组件-->
<!--<script src="/static/admin/js/plugins/zTree/jquery.ztree.exedit-3.5.js"></script>--><!--zTree编辑组件-->
<script src="/static/admin/js/plugins/webuploader/webuploader.js"></script><!--webUploader上传组件-->
<script src="/static/admin/js/plugins/wangEditor-3.1.1/release/wangEditor.js" ></script><!--wangEditor编辑器-->
<script src="/static/admin/js/plugins/wx-audio/wx-audio.js" ></script><!--音频播放器组件-->
<script src="/static/admin/js/plugins/clipboard/clipboard.js" ></script><!--粘贴板组件-->
<script src="/static/admin/js/plugins/jqprint/jQuery.print.js" ></script><!--打印组件-->
<script src="/static/admin/js/plugins/toastr/toastr.js" ></script><!--toastr通知组件-->
<script src="/static/admin/js/plugins/ueditor/ueditor.config.js" ></script><!--百度富文本-->
<script src="/static/admin/js/plugins/ueditor/ueditor.all.js" ></script><!--百度富文本-->
<!--<script type="text/javascript" src="/static/assets/js/common.js?v=317"></script>-->
<script>
    layui.config({
        base: '/src/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
        , formSelects: 'formSelects-v4'
        , dropdown: 'dropdown'
    }).use(['index','dropdown','formSelects']),function(){
        var formSelects = layui.formSelects
    };
</script>
<script>
   toastr.options = {
        "newestOnTop": false, //新的toastr会显示在旧的toastr前面
        "preventDuplicates": false, //重复内容的提示框只出现一次
        "target": "body", // 默认为'body', 设置toastr的目标容器
        "closeButton": true,//关闭按钮
        "debug": false,//调试模式
        "progressBar": true,//进度条
        "closeOnHover": true,//hover关闭
        "positionClass": "toast-bottom-right",//toastr显示位置
        "showDuration": "400",//显示的时间
        "hideDuration": "100",//消失的时间
        "timeOut": "7000",//停留的时间
        "extendedTimeOut": "100",//控制时间
        "showEasing": "swing",//显示时的动画缓冲方式
        "hideEasing": "linear",//消失时的动画缓冲方式
        "showMethod": "layui-anim layui-anim-up",//显示时的动画方式
        "hideMethod": "layui-anim layui-anim-fadeout",//消失时的动画方式
    }
    //view初始化查看图片
    $(function(){
        $('.layui-append-img,.layui-circle').viewer({
            url: 'data-original',
        });
    })

    //关闭自动填充
    $('input').attr('autocomplete',"off");

    // //图片灯箱
    // function imgDisplay(obj) {
    //     var src = $(obj).attr("src");
    //     var imgHtml = '<div class="showP"><img src=' + src + ' style="margin-top: 120px;height:50%;margin-bottom:120px;"  /><p class="closeP"  onclick="closePicture(this)"><span class="cha">×</span></p></div>'
    //     $('body').append(imgHtml);
    // }
    //
    // //关闭图片灯箱
    // function closePicture(obj) {
    //     $(obj).parent("div").remove();
    // }

    //tips框
    $('#offAll').on('mouseover', function(){
        var that = this;
        layer.tips('<span style="color:#686B6D;"><i class="fa fa-info-circle"></i> 若未勾选默认禁用全部</span>', that,{tips: [2, '#F2F2F2'],time: 10000});
    });
    //tips框
    $('#onAll').on('mouseover', function(){
        var that = this;
        layer.tips('<span style="color:#686B6D;"><i class="fa fa-info-circle"></i> 若未勾选默认启用全部</span>', that,{tips: [2, '#F2F2F2',''],time: 10000});
    });
    //tips框
    $('#excel').on('mouseover', function(){
        var that = this;
        layer.tips('<span style="color:#686B6D;"><i class="fa fa-info-circle"></i> 导出筛选完成数据</span>', that,{tips: [2, '#F2F2F2',''],time: 10000});
    });
    //关闭tips框
    $('#offAll,#onAll,#export,#excel').on('mouseout', function(){
        layer.closeAll('tips');
    });

    //layui公共操作
    layui.use(['form','table'], function() {
        var form = layui.form
            ,table = layui.table
        //重置搜索框
        $('#empty').on('click', function () {
            $('.layui-input').val('');
            // $(".search").trigger("chosen:updated");
            $('select').each(function (i, j) {
                $(j).find("option:selected").attr("selected", false);
                form.render('select')
            })
        });
        //表格排序
        table.on('sort(LAY-table-manage)', function(obj){
            table.reload('LAY-table', {
                initSort: obj //记录初始排序，如果不设的话，将无法标记表头的排序状态
                ,where: { //请求参数
                    field: obj.field //排序字段
                    ,order: obj.type //排序方式
                }
            });
        });
        //监听搜索
        form.on('submit(LAY-search)', function (data) {
            //执行重载
            table.reload('LAY-table', {
                page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: data.field
            });
        });
        //地区三级联动
        form.on('select(province)', function(data){
            getArea("province",data.value);
        });
        form.on('select(city)', function(data){
            getArea("city",data.value);
        });
        function getArea(type,id){
            $.ajax({
                url:"<?php echo url('admin/Base/place'); ?>",
                dataType:"json",
                data:'id='+id,
                type:'post',
                success:function(res){
                    var opt = null;
                    $.each(res.msg,function(key,vo){
                        opt = opt+"<option value="+vo.district_id+">"+vo.district+"</option>";
                    })
                    if(type=="province"){
                        $("#city").empty();
                        $("#city").append('<option value="">---- 请选择市 ----</option>');
                        $("#district").empty();
                        $("#district").append('<option value="">---- 请选择区 ----</option>');
                        $("#city").append(opt);
                    }else if(type == "city"){
                        $("#district").empty();
                        $("#district").append('<option value="">---- 请选择区 ----</option>');
                        $("#district").append(opt);
                    }
                    form.render('select');
                }
            })
        }
    });
</script>
<script>
    layui.use(['index', 'table'], function () {
        var $ = layui.$
            , form = layui.form
            , table = layui.table
        table.render({
            elem: '#LAY-table-manage'
            , url: '<?php echo url("Role/index"); ?>'
            ,response: {
                statusCode: 220 //成功的状态码，默认：0
            }
            , page: true
            , even: false //开启隔行背景
            , size: 'lg' //sm小尺寸的表格 lg大尺寸
            // ,width:100
            , autoSort: false
            , cellMinWidth: 150
            , height: "full-220"
            , limits: [10, 20, 30, 40, 50]
            , limit: "<?php echo config('pages'); ?>"
            , loading: true
            , id: 'LAY-table'
            , cols: [[
                {type: 'checkbox', fixed: 'left',}
                , {type:'numbers', width: 80, title: '序号'}
                , {field: 'title', width: '', title: '角色名称', align: 'center'}
                , {field: 'describe', width: '', title: '角色描述', align: 'center'}
                , {field: 'create_time', width: '', title: '添加时间', sort: true, align: 'center'}
                , {field: 'update_time', width: '', title: '更新时间', sort: true, align: 'center'}
                , {field: 'status',fixed: 'right', width: 100, title: '状态', align: 'center', templet: '#staBar'}
                , {fixed: 'right', width: 140, title: '操作', align: 'center', toolbar: '#opeBar'}
            ]]
        });
        //监听状态开关操作
        form.on('switch(OnOff)', function (obj) {
            var num = '';
            obj.elem.checked == true? num = 1: num = 2;
            //角色状态
            wk.status(this.value,num, '<?php echo url("role_state"); ?>',obj);
        });
        //事件
        var active = {
            getCheckData: function(){
                //批量删除
                wk.batchDel(getIds(),"<?php echo url('batchDelRole'); ?>");
            }
            ,forbiddenAll: function(){
                //批量禁用
                wk.batchForbidden(getIds(),2,"<?php echo url('forbiddenRole'); ?>");
            }
            ,usingAll: function(){
                //批量启用
                wk.usingAll(getIds(),1,"<?php echo url('usingRole'); ?>");
            }
        };

        $('.layuiBtn').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        var getIds = function () {
            var ids = [];
            var checkStatus = table.checkStatus('LAY-table')
                ,data = checkStatus.data;
            $.each(data,function(index,item){
                ids.push(item['id'])
            });
            return ids;
        }
    });
</script>
<script type="text/javascript">
    zNodes = '';
    var index = '';
    var index2 = '';
    //分配权限
    function giveQx(id){
        $("#nodeid").val(id);
        //加载层
        index2 = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
        //获取权限信息
        $.getJSON("<?php echo url('giveAccess'); ?>", {'type' : 'get', 'id' : id}, function(res){
            layer.close(index2);
            if(res.code == 200){
                zNodes = JSON.parse(res.data);  //将字符串转换成obj

                //页面层
                var index = wk.layer_show1("权限分配",$('#role'),400);
                //设置位置
                layer.style(index, {
                    top: '5%'
                });

                //设置zetree
                var setting = {
                    view: {
                        dblClickExpand: false,//是否双击展开
                        showLine: true,//设置为显示或隐藏线条
                        selectedMulti: false,//设置是否允许选择多个节点
                        expandSpeed: "slow",//节点展开动画速度  "slow"、 "normal"、"fast"
                        showIcon: true,//设置为显示或隐藏节点图标
                        // fontCss : {color:"red"},//个性化的文本样式
                        dblClickExpand: true, //双击展开父节点的功能
                    },
                    check:{
                        enable:true,//设置为接受简单数据格式
                        chkStyle: "checkbox",//复选框类型
                        chkboxType: { "Y": "ps", "N": "s" }//当一个节点被选中或未选中时，控制其父节点及其子节点自动选中或取消选中p:父节点  s:子节点
                    },
                    data: {
                        simpleData: {
                            enable: true,//将zTree的节点设置为接受简单数据格式
                        }
                    }
                };

                $.fn.zTree.init($("#treeType"), setting, zNodes);
                var zTree = $.fn.zTree.getZTreeObj("treeType");
                zTree.expandAll(true);

            }else if(res.code == 100){
                wk.error(res.msg);
            }
        });
    }

    //确认分配权限
    $("#postform").click(function(){
        var zTree = $.fn.zTree.getZTreeObj("treeType");
        var nodes = zTree.getCheckedNodes(true);
        var NodeString = '';
        $.each(nodes, function (n, value) {
            if(n>0){
                NodeString += ',';
            }
            NodeString += value.id;
        });
        var id = $("#nodeid").val();
        //写入库
        $.post("<?php echo url('giveAccess'); ?>", {'type' : 'give', 'id' : id, 'rule' : NodeString}, function(res){
            layer.closeAll();
            if(res.code == 200){
                wk.success(res.msg);
            }else if(res.code == 100){
                wk.error(res.msg);
            }

        }, 'json')
    })
</script>
</body>
</html>