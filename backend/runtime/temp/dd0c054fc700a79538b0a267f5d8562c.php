<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"/www/wwwroot/test1.sxjiangyan.com/public/../application/admin/view/public/error.tpl";i:1606658628;}*/ ?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <script src="/static/admin/js/layui/layui.js"></script>
</head>
<body>
    <script type="text/javascript">
        layer.msg('<?php echo $msg?>',{icon:7,shade:0.1});
        setTimeout(function(){
            location.href = '<?php echo($url);?>';
        },1500);
    </script>
</body>
</html>
