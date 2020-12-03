<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <script src="__JS__/layui/layui.js"></script>
</head>
<body>
    <script> layer.msg('<?php echo $msg?>',{icon:1,shade:0.1});</script>
    <script type="text/javascript">    
        	setTimeout(function(){
				location.href = '<?php echo($url);?>';
			},1500);
    </script>
</body>
</html>
