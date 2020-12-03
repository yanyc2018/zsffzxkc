<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <script src="__JS__/layui/layui.js"></script>
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
