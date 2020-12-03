$(function () {
    //.ajaxError事件定位到document对象，文档内所有元素发生ajax请求异常，都将冒泡到document对象的ajaxError事件执行处理
    $(document).ajaxComplete(function(event,request, settings){
        // console.log(event);
        // console.log(request);
        // console.log(settings);
    });
    $(document).ajaxSuccess(function(event,xhr,options){
        // console.log(xhr.responseJSON.code);
        if(xhr.responseJSON.code == 0){
            if(xhr.responseJSON.msg != ''){
                layer.closeAll();
                layer.msg(xhr.responseJSON.msg,{icon:7,time:1500,shade: 0.1,anim:6});
            }
            if(xhr.responseJSON.url&&xhr.responseJSON.url!=""){
                setTimeout(function(){
                    window.location.href = xhr.responseJSON.url;
                },2000);
                return false;
            }
        }
    })

    $(document).ajaxError(
        //所有ajax请求异常的统一处理函数，处理
        function (event, xhr, options, exc) {
            if (xhr.status == 'undefined') {
                return false;
            }
            switch (xhr.status) {
                case 403:
                    layer.msg('403:禁止访问...',{icon: 2,time:1500,anim:6},function(index){
                        layer.close(index);
                    });
                    break;
                case 404:
                    layer.msg('404:请求服务器出错...',{icon: 2,time:1500,anim:6},function(index){
                        layer.close(index);
                    });
                    break;
                case 500:
                    layer.msg('500:服务器错误...',{icon: 2,time:1500,anim:6},function(index){
                        layer.close(index);
                    });
                    break;
            }
        }
    );
});