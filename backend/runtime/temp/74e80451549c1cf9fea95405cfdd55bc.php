<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/login.html";i:1606658628;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <script>if (window !== top) top.location.replace(location.href);</script>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="/static/assets/images/favicon.ico" rel="icon">
    <title>登录</title>
    <link rel="stylesheet" href="/static/assets/libs/layui/css/layui.css"/>
    <link rel="stylesheet" href="/static/assets/module/admin.css?v=317">
    <link rel="stylesheet" href="/static/admin/css/login.css">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .getcode{
            position: absolute;
            right: 25px;
            cursor:pointer;
            border: none;
            background-color: transparent;
            outline: none;
        }
    </style>
</head>
<body>
<div class="login-wrapper layui-anim layui-anim-scale">
    <form class="layui-form" id="form1">
        <?php if($op=='login'): ?>
        <h2>用户登录</h2>
        <div class="layui-form-item layui-input-icon-group">
            <i class="layui-icon layui-icon-username"></i>
            <input class="layui-input" name="username" placeholder="请输入登录账号/手机号" autocomplete="off"
                   lay-verType="tips" lay-verify="required" required/>
        </div>
        <?php elseif($op=='reg'): ?>
        <h2>用户注册</h2>
        <div class="layui-form-item layui-input-icon-group">
            <i class="layui-icon layui-icon-username"></i>
            <input class="layui-input" name="username" placeholder="请输入账号" autocomplete="off"
                   lay-verType="tips" lay-verify="required" required/>
        </div>
        <?php endif; ?>
        <div class="layui-form-item layui-input-icon-group">
            <i class="layui-icon layui-icon-password"></i>
            <input class="layui-input" name="password" placeholder="请输入登录密码" type="password"
                   lay-verType="tips" lay-verify="required" required/>
        </div>
        <?php if($op=='reg'): ?>
        <div class="layui-form-item layui-input-icon-group">
            <i class="layui-icon layui-icon-key"></i>
            <input class="layui-input" name="password2" placeholder="请再次输入登录密码" type="password"
                   lay-verType="tips" lay-verify="required" required/>
        </div>
        <div class="layui-form-item layui-input-icon-group">
            <i class="layui-icon layui-icon-cellphone"></i>
            <input class="layui-input" name="phone" placeholder="请输入手机号" autocomplete="off"
                   lay-verType="tips" lay-verify="required" required/>
        </div>
        <?php endif; ?>
        <div class="layui-form-item layui-input-icon-group login-captcha-group" style="display: flex;align-items: center;">
            <?php if(config('verify_type') == 1): ?>
            <i class="layui-icon layui-icon-auz"></i>
            <input class="layui-input" name="vercode" id="LAY-user-login-vercode" placeholder="请输入验证码" autocomplete="off"
                   lay-verType="tips" lay-verify="required" required/>
            <?php if($op=='login'): ?><img class="login-captcha" src="<?php echo url('checkVerify'); ?>" onclick="javascript:this.src='<?php echo url('checkVerify'); ?>?tm='+Math.random();" id="verify" alt=""/>
            <?php elseif($op=='reg'): ?><button id="getcode" class="getcode layui-link">获取验证码</button>
            <?php endif; elseif(config('verify_type') == 2): ?>
            <div class="layui-col-xs12" id="geeFa">
                <div id="embed-captcha"></div>
            </div>
            <p id="wait">正在加载验证码.... <i class="layui-icon layui-icon-loading-1 layui-icon layui-anim layui-anim-rotate layui-anim-loop"></i></p>
            <?php endif; ?>
        </div>
        <?php if($op=='login'): ?>
        <div class="layui-form-item">
            <input type="checkbox" name="remember" title="记住密码" lay-skin="primary" checked>
            <a href="<?php echo $domain; ?>/index.php/admin/login?op=reg" class="layui-link pull-right">注册账号</a>
        </div>
        <?php elseif($op=='reg'): ?>
        <div class="layui-form-item">
            <a href="<?php echo $domain; ?>/index.php/admin/login?op=login" class="layui-link">返回登录</a>
            <a href="forget.html" class="layui-link pull-right">忘记密码</a>
        </div>
        <?php endif; ?>
        <div class="layui-form-item">
            <?php if($op=='login'): ?>
            <button class="layui-btn layui-btn-fluid" lay-filter="LAY-login" lay-submit>登录</button>
            <?php elseif($op=='reg'): ?>
            <button class="layui-btn layui-btn-fluid" lay-filter="LAY-reg" lay-submit>注册</button>
            <?php endif; ?>
        </div>

<!--        <div class="layui-form-item login-oauth-group text-center">-->
<!--            <a href="javascript:;"><i class="layui-icon layui-icon-login-qq" style="color:#3492ed;"></i></a>&emsp;-->
<!--            <a href="javascript:;"><i class="layui-icon layui-icon-login-wechat" style="color:#4daf29;"></i></a>&emsp;-->
<!--            <a href="javascript:;"><i class="layui-icon layui-icon-login-weibo" style="color:#CF1900;"></i></a>-->
<!--        </div>-->
    </form>
</div>
<div class="login-copyright"><?php echo $copyright['value']; ?></div>
<style>

</style>

<!-- js部分 -->
<script type="text/javascript" src="/static/assets/libs/layui/layui.js"></script>
<script type="text/javascript" src="/static/assets/js/common.js?v=317"></script>
<script src="/static/admin/js/layui/layui.all.js"></script>
<script src="/static/admin/js/jquery.min.js"></script>
<script src="/static/admin/js/wk.js"></script>
<script>
    layui.use('layer', function(){
        var layer = layui.layer;
        $('#getcode').click(function () {
            var time = 60;
            var phone=$("input[name='phone']").val()
            if(phone==''){
                layer.msg('手机号码不能为空');
                return false;
            }
            else if(!(/^1(3|4|5|6|7|8|9)\d{9}$/.test(phone))){
                layer.msg('手机号码格式不正确,请重试');
                return false;
            }else{
                var flag = true;
            }
            if(flag){
                $(this).attr("disabled",true);
                var timer = setInterval( () =>{
                    if(time == 60 && flag){
                        flag = false
                        $.ajax({
                            url: "<?php echo url('sendcode'); ?>",
                            type: "POST",
                            dataType: "json",
                            data:{
                                phone:phone
                            },
                            success: function (res) {
                                console.log(res)
                                if(res.code==1){
                                    layer.msg('发送成功');
                                }
                            }
                        });
                    }else if(time == 0){
                        clearInterval(timer);
                        $(this).removeAttr("disabled");
                        time = 60;
                        flag = false
                        $(this).text("获取验证码")
                    }else {
                        flag = true
                        $(this).text(time+' s 后重试')
                        time--;
                    }
                },1000);
            }
        });

    });

    $('#LAY-user-login-username').focus();
    var handlerEmbed = function (captchaObj) {
        $("#embed-submit").click(function (e) {
            var validate = captchaObj.getValidate();
            if (!validate) {
                $("#notice")[0].className = "show";
                setTimeout(function () {
                    $("#notice")[0].className = "hide";
                }, 2000);
                e.preventDefault();
            }
        });
        // 将验证码加到id为captcha的元素里
        captchaObj.appendTo("#embed-captcha");
        captchaObj.onReady(function () {
            $("#wait")[0].className = "layui-hide";
        });
        // 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
    };
    var geeCheck = function(){
        $.ajax({
            // 获取id，challenge，success（是否启用failback）
            url: "<?php echo url('getVerify',array('t'=>time())); ?>", // 加随机数防止缓存
            type: "get",
            dataType: "json",
            success: function (data) {
                // 使用initGeetest接口
                // 参数1：配置参数
                // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
                initGeetest({
                    gt: data.gt,
                    challenge: data.challenge,
                    product: "float", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                    offline: !data.success, // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                    width:"100%",
                }, handlerEmbed);
            }
        });
    }
    if('<?php echo config('verify_type'); ?>' == 2){
        geeCheck();
    }
    layui.use(['layer', 'form'], function(){
        var form = layui.form;
        var layer = layui.layer;
        //监听提交
        form.on('submit(LAY-login)', function(data){
            $(".layui-btn").addClass('layui-disabled').attr('disabled','disabled').html('登录中... &nbsp;<i class="layui-icon layui-icon-loading-1 layui-icon layui-anim layui-anim-rotate layui-anim-loop"></i>');
            $.ajax({
                url:"<?php echo url('doLogin'); ?>",
                type:'post',
                dataType:'json',
                data:data.field,
                success:function(res){
                    if(res.code == 1){
                        setTimeout(function(){
                            location.href=res.url;
                        },0.5);
                    }else{
                        layer.msg(res.msg,{icon:2,time:1500,anim: 6},function(index){
                            if('<?php echo config('verify_type'); ?>' == 2 && res.code != -3){
                                $("#wait").removeClass('layui-hide');
                                $('#embed-captcha').remove();
                                $('#geeFa').append('<div id="embed-captcha"></div>')
                                geeCheck();
                            }else if('<?php echo config('verify_type'); ?>' == 1){
                                $('#verify').attr("src","<?php echo url('checkVerify'); ?>?tm="+Math.random());
                            }
                            switch(res.code)
                            {
                                case -1:
                                    $('#LAY-user-login-username').focus();
                                    break;
                                case -2:
                                    $('#LAY-user-login-password').focus();
                                    break;
                                case -4:
                                    $('#LAY-user-login-vercode').focus();
                                    break;
                            }
                            layer.close(index);
                        })
                        $(".layui-btn").removeClass('layui-disabled').removeAttr('disabled').text("登 录");
                        return false;
                    }
                }
                ,error:function(event, xhr, options, exc){
                    switch (event.status) {
                        case 403:
                            wk.error('403:禁止访问...');
                            break;
                        case 404:
                            wk.error('404:请求服务器出错...');
                            break;
                        case 500:
                            wk.error('500:服务器错误...');
                            break;
                    }
                    $(".layui-btn").removeClass('layui-disabled').removeAttr('disabled').text("登 录");
                }
            })
        });
        form.on('submit(LAY-reg)', function(data){
            if(data.field.password != data.field.password2){
                layer.msg('两次密码输入不一致,请重试！');
                return false;
            }
            $(".layui-btn").addClass('layui-disabled').attr('disabled','disabled').html('注册中... &nbsp;<i class="layui-icon layui-icon-loading-1 layui-icon layui-anim layui-anim-rotate layui-anim-loop"></i>');
            $.ajax({
                url:"<?php echo url('reg'); ?>",
                type:'post',
                dataType:'json',
                data:data.field,
                success:function(res){
                    if(res.code == 0 || res.code == 2){
                        layer.msg(res.msg);
                        setTimeout(function(){
                            location.href="<?php echo $domain; ?>/index.php/admin/login?op=login";
                        },1000);
                    }else{
                        layer.msg(res.msg,{icon:2,time:1500,anim: 6})
                        $(".layui-btn").removeClass('layui-disabled').removeAttr('disabled').text("注 册");
                        return false;
                    }
                }
                ,error:function(event, xhr, options, exc){
                    switch (event.status) {
                        case 403:
                            wk.error('403:禁止访问...');
                            break;
                        case 404:
                            wk.error('404:请求服务器出错...');
                            break;
                        case 500:
                            wk.error('500:服务器错误...');
                            break;
                    }
                    $(".layui-btn").removeClass('layui-disabled').removeAttr('disabled').text("注 册");
                }
            })
        });
    });
    //防止页面后退
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });

</script>
</body>
</html>