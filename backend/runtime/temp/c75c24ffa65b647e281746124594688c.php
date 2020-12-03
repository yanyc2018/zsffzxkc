<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/set/basic.html";i:1606658624;}*/ ?>
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
            width: 850px;
            margin: 30px 250px;
        }

        .layui-form-item {
            display: flex;
        }

        .layui-form-label {
            flex: 4;
        }

        .layui-input-block {
            flex: 14;
        }

        .layui-input-block {
            margin-left: 20px !important;
            min-height: 36px;
        }

        .pictureNomer {
            flex: 7;
            height: 38px;
            line-height: 38px;
            margin-right: 5px;
        }
        .layui-upload-img {

            max-height: 200px;
            height: auto;
            margin: 0 10px 10px 0;
            cursor:pointer;
        }
        .imgs-box{
            display: flex;
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
            <div class="layui-card-body">
                <!-- 表单开始 -->
                <form class="layui-form" id="formBasForm" lay-filter="formBasForm">
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">店铺标题:</label>
                        <div class="layui-input-block">
                            <input name="title" class="layui-input" value="<?php echo $item['title']; ?>" lay-verify="required" required  />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">联系电话:</label>
                        <div class="layui-input-block">
                            <input name="phone" class="layui-input" value="<?php echo $item['phone']; ?>" lay-verify="required" required />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">分享标题:</label>
                        <div class="layui-input-block">
                            <input name="share_title" class="layui-input" value="<?php echo $item['share_title']; ?>" lay-verify="required" required />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">分享描述:</label>
                        <div class="layui-input-block">
                            <input name="share_desc" class="layui-input" value="<?php echo $item['share_desc']; ?>" lay-verify="required" required />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">分享图标:</label>
                        <input type="hidden" name="share_icon" lay-verify="required" required value="<?php echo !empty($item['share_icon'])?$item['share_icon']:''; ?>">
                        <div class="layui-upload layui-input-block">
                            <button type="button" class="layui-btn" id="test1"><i class="layui-icon">&#xe67c;</i>上传图片</button>
                            <div class="layui-upload-list">
                                <img class="layui-upload-img" src="<?php echo !empty($item['share_icon'])?$item['share_icon']:''; ?>" id="demo1">
                                <p id="demoText"></p>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">学习时长兑换积分：</label>
                        <div class="layui-input-block">
                            <div style="display: flex;">
                                <div style="display: flex; margin-right: 20px;justify-content: center;align-items: center; ">
                                    <p class="pictureNomer">每学习</p><input type="number" name="study_minute" class="layui-input"
                                                                         style="flex: 15;" lay-verify="required" required min="0"  value="<?php echo $item['study_minute']; ?>" /><span>&nbsp;&nbsp;分钟</span>
                                </div>
                                <div style="display: flex;justify-content: center;align-items: center; ">
                                    <p class="pictureNomer">可兑换</p><input type="number" name="dh_score" class="layui-input"
                                                                         style="flex: 15;" lay-verify="required" required min="0"  value="<?php echo $item['dh_score']; ?>" /><span>&nbsp;&nbsp;积分</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block" style="display: flex;
                    justify-content: space-around;
                ">
                            <button id="btnDemoEdtGetContent" class="layui-btn submitbtn" lay-filter="formBasSubmit"
                                lay-submit>&emsp;提交&emsp;</button>
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
    <script type="text/javascript" src="/static/assets/libs/tinymce/tinymce.min.js"></script>
    <script src="/static/assets/libs/jquery/jquery-3.2.1.min.js"></script>
    <script>

        layui.use(['layer', 'form', 'laydate', 'jquery','upload','admin'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var form = layui.form;
            var laydate = layui.laydate;
            var upload = layui.upload;
            var admin = layui.admin;
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
                        $("input[name='share_icon']").val(res.data);
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
            /* 监听表单提交 */
            form.on('submit(formBasSubmit)', function (data) {
                $('.submitbtn').addClass('layui-disabled').attr('disabled','disabled');
                $.ajax({
                    url:"<?php echo url('basic'); ?>",
                    type:'POST',
                    dataType:'json',
                    data:data.field,
                    success:function(res){
                        //console.log(res)
                        if (res.code == 0) {
                            layer.msg(res.msg,{icon:1,time:1500,shade:0.1})
                        } else {
                            $(".layui-btn").removeClass('layui-disabled').removeAttr('disabled');
                            wk.error(res.msg);
                            return false;
                        }
                    }
                })
            });
        });
    </script>
</body>

</html>