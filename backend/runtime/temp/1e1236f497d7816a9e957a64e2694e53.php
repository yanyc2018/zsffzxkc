<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/course/add_menu.html";i:1606890343;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>基础表单</title>
    <link rel="stylesheet" href="/static/assets/libs/layui/css/layui.css" />
    <link rel="stylesheet" href="/static/assets/module/admin.css?v=317" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        #formBasForm {
           max-width: 800px;
            margin: 30px 200px 50px;
        }

        .layui-form-item {
            display: flex ;
        }

        .layui-form-label {
            flex: 3;
        }

        .layui-input-block {
            flex: 14;
        }

        .layui-input-block {
            margin-left: 20px !important;
            min-height: 36px;
        }
        .layui-upload-img {
            width: 200px;
            max-height: 120px;
            height: auto;
            margin: 0 10px 10px 0;
            cursor:pointer;
        }
        #formBasForm .layui-form-item {
            margin-bottom: 25px;
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
            <ul class="layui-tab-title" style="margin-bottom: 20px;">
                <li ><a ew-href="<?php echo url('index'); ?>" ew-title="课程管理">课程管理</a></li>
                <li class="layui-this"><a href="javascript:location.reload();">添加目录</a></li>
                <li><a ew-href="<?php echo url('add_course'); ?>" ew-title="添加课程">添加课程</a></li>
<!--                <li><a ew-href="<?php echo url('menu_orderlist'); ?>" ew-title="单目录购买记录">购买记录</a></li>-->
            </ul>
            <div class="layui-card-body">
                <!-- 表单开始 -->
                <form class="layui-form" id="formBasForm" lay-filter="formBasForm">
                    <input type="hidden" name="id" value="<?php echo !empty($item['id'])?$item['id']:''; ?>">
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">选择媒体类型:</label>
                        <div class="layui-input-block">
                            <input id="media" name="media" value="<?php echo !empty($item['media'])?$item['media']:''; ?>" placeholder="请选择" class="layui-hide" required lay-verify="required" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">目录名称:</label>
                        <div class="layui-input-block">
                            <input name="menuname" class="layui-input" required lay-verify="required" value="<?php echo !empty($item['menuname'])?$item['menuname']:''; ?>"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">封面:</label>
                        <input type="hidden" name="thumb" lay-verify="required" required value="<?php echo !empty($item['thumb'])?$item['thumb']:''; ?>">
                        <div class="layui-upload layui-input-block">
                            <button type="button" class="layui-btn" id="test1"><i class="layui-icon">&#xe67c;</i> 上传图片</button>
                            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                预览图：
                                <div class="layui-upload-list" id="lay-img-list">
                                    <img class="layui-upload-img" src="<?php echo !empty($item['thumb'])?$item['thumb']:''; ?>" id="demo1">
                                    <p id="demoText"></p>
                                </div>
                            </blockquote>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">选择讲师:</label>
                        <div class="layui-input-block">
                            <input id="teacher" name="tid" value="<?php echo !empty($item['tid'])?$item['tid']:''; ?>" placeholder="请选择" class="layui-hide" required lay-verify="required" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">选择分类：</label>
                        <div class="layui-input-block">
                            <input id="classify" name="fenleiid" value="<?php echo !empty($item['fenleiid'])?$item['fenleiid']:''; ?>" placeholder="请选择" class="layui-hide" required lay-verify="required" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">概述:</label>
                        <div class="layui-input-block">
                            <input name="jianjie" class="layui-input" required lay-verify="required" value="<?php echo !empty($item['jianjie'])?$item['jianjie']:''; ?>" placeholder="一句话描述"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">目录课程介绍:</label>
                        <div class="layui-input-block">
                            <div class="layui-input-block" >
                                <textarea name="introduce" id="LAY_editor2" ><?php echo !empty($item['introduce'])?$item['introduce']:''; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">价格:</label>
                        <div class="layui-input-block">
                            <input name="price" class="layui-input" required lay-verify="required" value="<?php echo !empty($item['price'])?$item['price']:''; ?>" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">赠送积分:</label>
                        <div class="layui-input-block">
                            <input name="zsscore" class="layui-input" value="<?php echo !empty($item['zsscore'])?$item['zsscore']:''; ?>" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">单课程解锁</label>
                        <div class="layui-input-block">
                            <?php if($item): ?>
                            <input type="checkbox" <?php if($item['one_lock']==1): ?>checked<?php endif; ?> name="one_lock" lay-skin="switch" lay-filter="switchTest" lay-text="开|关">
                            <?php else: ?>
                            <input type="checkbox" checked name="one_lock" lay-skin="switch" lay-filter="switchTest" lay-text="开|关">
                            <?php endif; ?>
                        </div>
                    </div>
<!--                    <div class="layui-form-item">-->
<!--                        <label class="layui-form-label">是否设为专栏:</label>-->
<!--                        <div class="layui-input-block">-->
<!--                            <?php if($item): ?>-->
<!--                            <input type="checkbox" <?php if($item['is_zl']==1): ?>checked<?php endif; ?> name="is_zl" lay-skin="switch" lay-filter="switchTest" lay-text="是|否">-->
<!--                            <?php else: ?>-->
<!--                            <input type="checkbox" name="is_zl" lay-skin="switch" lay-filter="switchTest" lay-text="是|否">-->
<!--                            <?php endif; ?>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="layui-form-item">-->
<!--                        <label class="layui-form-label">是否开启分销:</label>-->
<!--                        <div class="layui-input-block">-->
<!--                            <?php if($item): ?>-->
<!--                            <input type="checkbox" <?php if($item['is_fx']==1): ?>checked<?php endif; ?> name="is_fx" lay-skin="switch" lay-filter="switchTest" lay-text="是|否">-->
<!--                            <?php else: ?>-->
<!--                            <input type="checkbox" name="is_fx" lay-skin="switch" lay-filter="switchTest" lay-text="是|否">-->
<!--                            <?php endif; ?>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否上架:</label>
                        <div class="layui-input-block">
                            <?php if($item): ?>
                            <input type="checkbox" <?php if($item['status']==1): ?>checked<?php endif; ?> name="status" lay-skin="switch" lay-filter="switchTest" lay-text="是|否">
                            <?php else: ?>
                            <input type="checkbox" checked name="status" lay-skin="switch" lay-filter="switchTest" lay-text="是|否">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否推荐:</label>
                        <div class="layui-input-block">
                            <?php if($item): ?>
                            <input type="checkbox" <?php if($item['is_tj']==1): ?>checked<?php endif; ?> name="is_tj" lay-skin="switch" lay-filter="switchTest" lay-text="是|否">
                            <?php else: ?>
                            <input type="checkbox" checked name="is_tj" lay-skin="switch" lay-filter="switchTest" lay-text="是|否">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block" style="display: flex;justify-content: space-around;">
                            <button id="btnDemoEdtGetContent" class="layui-btn" lay-filter="formBasSubmit" lay-submit>&emsp;提交&emsp;</button>
                            <button type="reset" class="layui-btn layui-btn-primary">&emsp;重置&emsp;</button>
                        </div>
                    </div>
                </form>
                <!-- //表单结束 -->
            </div>
        </div>
    </div>

    <!-- js部分 -->
    <script type="text/javascript" src="/static/assets/libs/layui/layui.js"></script>
    <script type="text/javascript" src="/static/assets/js/common.js?v=317"></script>
    <script src="/static/assets/libs/jquery/jquery-3.2.1.min.js"></script>
    <script src="/static/admin/js/plugins/ueditor/ueditor.config.js" ></script><!--百度富文本-->
    <script src="/static/admin/js/plugins/ueditor/ueditor.all.js" ></script><!--百度富文本-->
    <script>
        //百度富文本编辑器
        var editor = UE.getEditor('LAY_editor2', {
            initialFrameWidth:750,
            initialFrameHeight:450,
            autoHeight: false,
            autoHeightEnabled:false,
            autoFloatEnabled:false
        });
        //自定义上传接口
        UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
        UE.Editor.prototype.getActionUrl = function(action) {
            if (action == 'uploadimage' || action == 'uploadscrawl') {
                return '/index.php/admin/upload/ueditorUpload';//这就是自定义的上传地址
            } else {
                return this._bkGetActionUrl.call(this, action);
            }
        }
        layui.use(['layer', 'form', 'laydate','jquery','cascader','upload','admin'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var form = layui.form;
            var laydate = layui.laydate;
            var cascader = layui.cascader;
            var upload = layui.upload;
            var admin = layui.admin;
            var data = [{
                value: 'video',
                label: '视频',
            },{
                value: 'audio',
                label: '音频',
            },{
                value: 'tuwen',
                label: '图文',
            },
            {
                value: 'all',
                label: '所有',
            },
            ]

            cascader.render({
                elem: '#media',
                data: data
            });
            cascader.render({
                elem: '#teacher',
                reqData: function (values, callback, data) {
                    $.ajax({
                        url:"<?php echo url('admin/teacher/teacher_list_json'); ?>",
                        success:function(res){
                            //console.log(res)
                            if(res.code==0){
                                callback(res.data)
                            }
                        }
                    })
                },
                onChange: function (values, data) {
                    console.log(values);
                    console.log(data);
                }
            });
            cascader.render({
                elem: '#classify',
                reqData: function (values, callback, data) {
                    $.ajax({
                        url:"<?php echo url('admin/classify/classify_list_json'); ?>",
                        success:function(res){
                            //console.log(res)
                            if(res.code==0){
                                callback(res.data)
                            }
                        }
                    })
                },
                onChange: function (values, data) {
                    console.log(values);
                    console.log(data);
                }
            });
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#test1'
                ,url: "<?php echo url('admin/Upload/uploadLocal'); ?>" //改成您自己的上传接口
                ,before: function(obj){
                    //预读本地文件示例，不支持ie8
                    obj.preview(function(index, file, result){
                        $('#demo1').attr('src', result); //图片链接（base64）
                    });
                }
                ,done: function(res){
                    console.log(res)
                    //如果上传失败
                    if(res.code == 0){
                        $("input[name='thumb']").val(res.data);
                        var demoText = $('#demoText');
                        demoText.html('<span style="color: #01AAED;"><i class="layui-icon">&#x1005;</i>上传成功</span>');
                    }
                    //上传成功
                }
                ,error: function(){
                    //演示失败状态，并实现重传
                    var demoText = $('#demoText');
                    demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                    demoText.find('.demo-reload').on('click', function(){
                        uploadInst.upload();
                    });
                }
            });
            /* 渲染laydate */
            laydate.render({
                elem: '#formBasDateSel',
                trigger: 'click',
                range: true
            });

            /* 监听表单提交 */
            form.on('submit(formBasSubmit)', function (data) {
                $('.layui-btn').addClass('layui-disabled').attr('disabled','disabled');
                $.ajax({
                    url:"<?php echo url('add_menu'); ?>",
                    type:'post',
                    dataType:'json',
                    data:data.field,
                    success:function(res){
                        //console.log(res)
                        if (res.code == 0) {
                            layer.msg(res.msg,{icon:1,time:1500,shade:0.1})
                            setTimeout(function () {
                                admin.closeThisTabs();
                            }, 1000);
                        } else {
                            $(".layui-btn").removeClass('layui-disabled').removeAttr('disabled');
                            wk.error(res.msg);
                            return false;
                        }
                    }
                })
            });
            /* 点击图片放大 */
            $("#demo1").click(function () {
                layer.photos({photos: {data: [{src: $(this).attr('src')}]}, shade: .1, closeBtn: true});
            });
        });
    </script>
</body>

</html>