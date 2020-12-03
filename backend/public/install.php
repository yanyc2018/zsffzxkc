<!--/**-->
<!-- * 知识付费在线课程v2.0.0-->
<!-- * Author: 山西匠言网络科技有限公司-->
<!-- * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.-->
<!-- */-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>知识付费在线课程V2.0.0安装向导</title>
    <link rel="stylesheet" href="./static/assets/libs/layui/css/layui.css"/>
    <link rel="stylesheet" href="./static/assets/module/admin.css?v=318"/>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
[lay-filter="formStepsStep"] .layui-form-item {
    margin-bottom: 25px;
        }
    </style>
</head>
<body>
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body" style="padding-top: 40px;">
            <h1 style="text-align: center;margin: 38px 0;">知识付费在线课程V2.0.0安装向导</h1>
            <!-- 分布表单开始 -->
            <div class="layui-tab layui-steps layui-steps-readonly" lay-filter="formStepsStep"
                 style="max-width: 650px;">
                <!-- 标题 -->
                <ul class="layui-tab-title">
                    <li class="layui-this">
                        <i class="layui-icon layui-icon-ok">1</i>
                        <span class="layui-steps-title">第一步</span>
                        <span class="layui-steps-content">填写数据库信息</span>
                    </li>
                    <li>
                        <i class="layui-icon layui-icon-ok">2</i>
                        <span class="layui-steps-title">第二步</span>
                        <span class="layui-steps-content">开始安装</span>
                    </li>
                    <li>
                        <i class="layui-icon layui-icon-ok">3</i>
                        <span class="layui-steps-title">第三步</span>
                        <span class="layui-steps-content">安装成功</span>
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <!-- 表单一 -->
<!--                        <form class="layui-form" style="max-width: 460px;margin: 0 auto;padding: 40px 30px 0 0;" method="post">-->
<!--                            <input name="form" value="form1" type="hidden">-->
                            <div class="layui-form-item">
                                <label class="layui-form-label layui-form-required">数据库地址:</label>
                                <div class="layui-input-block">
                                    <input name="hostname" value="127.0.0.1" placeholder="请输入数据库地址"
                                           class="layui-input" lay-verType="tips" lay-verify="required" required>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label layui-form-required">数据库名:</label>
                                <div class="layui-input-block">
                                    <input name="database"  placeholder="请输入数据库名"
                                           class="layui-input" lay-verType="tips" lay-verify="required" required>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label class="layui-form-label layui-form-required">用户名:</label>
                                <div class="layui-input-block">
                                    <input name="username" placeholder="请输入用户名" class="layui-input"
                                           lay-verType="tips" lay-verify="required" required/>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label layui-form-required">密码:</label>
                                <div class="layui-input-block">
                                    <input name="password" placeholder="请输入密码" class="layui-input" type="password"
                                           lay-verType="tips" lay-verify="required" required>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button id="onenext" class="layui-btn" lay-filter="formStepSubmit1" lay-submit>&emsp;下一步&emsp;
                                    </button>
                                </div>
                            </div>
                    </div>
                    <div class="layui-tab-item">
                        <!-- 表单二 -->
                            <div class="layui-form-item" style="margin-top: 25px;display: flex;align-items: center;">
                                <label class="layui-form-label">目录权限:</label>
                                <div class="layui-form-mid ">application &nbsp;&nbsp;&nbsp;<span style="color: #5FB878;" id="checkinfo"></span></div>
                                <button class="layui-btn layui-btn-sm layui-btn-normal" id="two_check">&emsp;一键检测&emsp;
                                </button>
                            </div>
                            <div class="layui-progress layui-hide layui-progress-big" id="progress" lay-showpercent="true" lay-filter="demo" style="margin-bottom: 25px;">
                                <div class="layui-progress-bar layui-bg-red" lay-percent="0%"></div>
                            </div>
                            <div class="layui-form-item">
                                <span class="layui-form-label" id="backmsg"></span>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="button" class="layui-btn layui-btn-primary" data-steps="prev"> 上 一 步&nbsp;
                                    </button>
                                    <button class="layui-btn layui-btn-disabled site-demo-active" disabled id="installbtn" data-type="loading" lay-tips="请先检测目录权限">&emsp;开始安装&emsp;
                                    </button>
                                </div>
                            </div>
                    </div>
                    <div class="layui-tab-item text-center" style="padding-top: 40px;">
                        <!-- 表单三 -->
                        <i class="layui-icon layui-icon-ok layui-circle"
                           style="background: #52C41A;color: #fff;font-size:30px;font-weight:bold;padding: 20px;line-height: 80px;"></i>
                        <div style="font-size: 24px;color: #333;margin-top: 30px;">安装成功</div>
                        <div style="font-size: 14px;color: #666;margin-top: 20px;">后台地址：<span id="managerUrl"></span></div>
                        <div style="font-size: 14px;color: #666;margin-top: 20px;">账号：<span id="admin"></span></div>
                        <div style="font-size: 14px;color: #666;margin-top: 20px;">密码：<span id="password"></span></div>
                        <div style="text-align: center;margin: 50px 0 25px 0;">
                            <a id="loginbtn" target="_blank"><button class="layui-btn layui-btn-primary">登录后台</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //分布表单结束 -->
            <hr>
            <div style="padding: 10px 30px 20px 30px;">
                <h3>安装注意事项</h3><br>
                <h4>1.提示目录权限不足？</h4>
                <p class="layui-text">请保证application目录有读写权限</p>
                <br><h4>2.安装完成</h4>
                <p class="layui-text">安装完成后，请删除public目录下的install.php文件</p>
            </div>
        </div>
    </div>
</div>

<!-- js部分 -->
<script type="text/javascript" src="./static/assets/libs/layui/layui.js"></script>
<script type="text/javascript" src="./static/assets/js/common.js?v=318"></script>
<script>
layui.use(['layer', 'form', 'steps','element'], function () {
    var $ = layui.jquery;
    var layer = layui.layer;
    var form = layui.form;
    var steps = layui.steps;
    var element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
    $('#onenext').click(function () {
        if(!$("input[name='hostname']").val()){
            layer.msg('主机值不能为空',{icon:2,time:1000,shade:0.1})
            return false;
        }
        if(!$("input[name='database']").val()){
            layer.msg('数据库名不能为空',{icon:2,time:1000,shade:0.1})
            return false;
        }
        if(!$("input[name='username']").val()){
            layer.msg('用户名不能为空',{icon:2,time:1000,shade:0.1})
            return false;
        }
        if(!$("input[name='password']").val()){
            layer.msg('密码不能为空',{icon:2,time:1000,shade:0.1})
            return false;
        }
        var data={
            'hostname':$("input[name='hostname']").val(),
            'database':$("input[name='database']").val(),
            'username':$("input[name='username']").val(),
            'password':$("input[name='password']").val(),
            'form':'form1'
        }
        $.ajax({
            url:'./install1.php',
            type:'post',
            dataType:'json',
            data:data,
            success:function(res){
                console.log(res)
                if (res.code == 0) {
                    layer.msg(res.msg,{icon:1,time:1500,shade:0.1})
                    setTimeout(()=>{
                        steps.next('formStepsStep');
                        return false;
                    },1500)
                } else {
                    layer.msg(res.msg,{icon:2,time:1500,shade:0.1})
                    return false;
                }
            }
        })

    })
    /* 表单二提交事件 */
    form.on('submit(formStepSubmit2)', function (data) {
        steps.next('formStepsStep');
        return false;
    });
    $('#two_check').click(function () {
        var data={
            'form':'form2'
        }
        $.ajax({
            url:'./install1.php',
            type:'post',
            dataType:'json',
            data:data,
            success:function(res){
                console.log(res)
                if (res.code == 0) {
                    $("#installbtn").removeClass('layui-btn-disabled').removeAttr('disabled');
                    $("#checkinfo").html("<span style='color: #5FB878;'>可读写 √</span>");
                } else {
                    $("#checkinfo").html("<span style='color: #FF5722;'>无读写权限 × </span>");
                    return false;
                }
            }
        })
    })
    $('#installbtn').click(function () {
        $('#progress').removeClass('layui-hide');

    })


    //触发事件
    var active = {
        loading: function(othis){
            var DISABLED = 'layui-btn-disabled';
            if(othis.hasClass(DISABLED)) return;
            var data={
                'form':'form3'
            }
            $.ajax({
                url:'./install1.php',
                type:'post',
                dataType:'json',
                data:data,
                success:function(res){
                    console.log(res)
                    if (res.code == 0) {
                        window.installstate=0
                        var url = window.location.href;
                        var protocol = window.location.protocol;
                        var domain = url.split('/');
                        if( domain[2] ) {
                            domain = domain[2];
                        }
                        var manageUrl=protocol+'//'+domain+'/index.php/admin/login';
                        $("#managerUrl").text(manageUrl);
                        $("#admin").text('admin');
                        $("#password").text('123456');
                        $('#loginbtn').attr('href',manageUrl);
                    } else {
                        $("#backmsg").html(res.msg+res.data?res.data:'');
                        layer.msg(res.msg+res.data?res.data:'',{icon:2,time:1500,shade:0.1})
                    }
                }
            })
            //模拟loading
            var n = 0, timer = setInterval(function(){
                n = n + Math.random()*10|0;
                if(n>100 && window.installstate==0){
                    n = 100;
                    layer.msg('安装成功',{icon:1,time:1500,shade:0.1})
                    clearInterval(timer);
                    // othis.removeClass(DISABLED);
                    othis.val('安装成功');
                    setTimeout(()=>{
                        steps.next('formStepsStep');
                        return false;
                    },1500)
                }
                element.progress('demo', n+'%');
            }, 400+Math.random()*1000);

            othis.addClass(DISABLED);
        }
    };
    $('.site-demo-active').on('click', function(){
        var othis = $(this), type = $(this).data('type');
        active[type] ? active[type].call(this, othis) : '';
    });
});
</script>
</body>
</html>