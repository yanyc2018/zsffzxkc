<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/set/tplmsg.html";i:1606658624;}*/ ?>
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
           max-width: 1000px;
            margin: 30px 200px 50px;
        }

        .layui-form-item {
            display: flex ;
        }

        .layui-form-label {
            flex: 16;
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
            
            <div class="layui-card-body">
                <!-- 表单开始 -->
                <form class="layui-form" id="formBasForm" lay-filter="formBasForm">
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">成员加入提醒(IT科技->互联网|电子商务 编号OPENTM207685059):</label>
                        <div class="layui-input-block">
                            <input name="msg_newuser" value="<?php echo $item['msg_newuser']; ?>" class="layui-input" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">提现成功通知(IT科技->互联网|电子商务 编号OPENTM411207151):</label>
                        <div class="layui-input-block">
                            <input name="msg_txok" value="<?php echo $item['msg_txok']; ?>" class="layui-input" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">预约课程开始提醒(IT科技->互联网|电子商务 编号OPENTM405456204):</label>
                        <div class="layui-input-block">
                            <input name="msg_kcstart" value="<?php echo $item['msg_kcstart']; ?>" class="layui-input" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">课程购买成功通知(教育->培训 编号OPENTM203829700):</label>
                        <div class="layui-input-block">
                            <input name="msg_kcbuyok" value="<?php echo $item['msg_kcbuyok']; ?>" class="layui-input" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">结算成功通知(IT科技->互联网|电子商务 编号OPENTM409909643):</label>
                        <div class="layui-input-block">
                            <input name="msg_newyj" value="<?php echo $item['msg_newyj']; ?>" class="layui-input" />
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">订单发货通知(IT科技->互联网|电子商务 编号OPENTM414956350):</label>
                        <div class="layui-input-block">
                            <input name="msg_fahuo" value="<?php echo $item['msg_fahuo']; ?>" class="layui-input" />
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
            /* 监听表单提交 */
            form.on('submit(formBasSubmit)', function (data) {
                $('.submitbtn').addClass('layui-disabled').attr('disabled','disabled');
                $.ajax({
                    url:"<?php echo url('tplmsg'); ?>",
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