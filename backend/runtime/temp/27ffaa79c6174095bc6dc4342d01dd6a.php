<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/shop/notice_add.html";i:1606658624;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>基础表单</title>
    <link rel="stylesheet" href="/static/assets/libs/layui/css/layui.css"/>
    <link rel="stylesheet" href="/static/assets/module/admin.css?v=317"/>
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
            <div class="layui-card-header">添加公告 </div>
            <div class="layui-card-body">
                <!-- 表单开始 -->
                <form class="layui-form" id="formBasForm" lay-filter="formBasForm">
                    <input type="hidden" name="id" value="<?php echo !empty($item['id'])?$item['id']:''; ?>">
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">公告标题:</label>
                        <div class="layui-input-block">
                            <input name="title" class="layui-input" required lay-verify="required" value="<?php echo !empty($item['title'])?$item['title']:''; ?>" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">公告内容:</label>
                        <div class="layui-input-block">
                            <input name="name" class="layui-input" required lay-verify="required" value="<?php echo !empty($item['name'])?$item['name']:''; ?>" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">小程序链接:</label>
                        <div class="layui-input-block">
                            <input name="src" class="layui-input" value="<?php echo !empty($item['src'])?$item['src']:''; ?>"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item" id="videoAdress">
                        <label class="layui-form-label layui-form-required">公众号链接:</label>
                        <div class="layui-input-block">
                            <input name="gzh_src" class="layui-input" value="<?php echo !empty($item['gzh_src'])?$item['gzh_src']:''; ?>"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <div class="layui-input-block" style="display: flex;
                    justify-content: space-around;
                ">
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
    <script>
        layui.use(['layer', 'form', 'admin'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var form = layui.form;
            var admin = layui.admin;
            /* 监听表单提交 */
            form.on('submit(formBasSubmit)', function (data) {
                $('.layui-btn').addClass('layui-disabled').attr('disabled','disabled');
                $.ajax({
                    url:"<?php echo url('notice_add'); ?>",
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
        });
    </script>
</body>

</html>