/** EasyWeb iframe v3.1.7 date:2020-03-11 License By http://easyweb.vip */

// 以下代码是配置layui扩展模块的目录，每个页面都需要引入
layui.config({
    version: '317',
    tabAutoRefresh: true,
    base: getProjectUrl() + 'assets/module/'
}).extend({
    steps: 'steps/steps',
    notice: 'notice/notice',
    cascader: 'cascader/cascader',
    dropdown: 'dropdown/dropdown',
    fileChoose: 'fileChoose/fileChoose',
    treeTable: 'treeTable/treeTable',
    Split: 'Split/Split',
    Cropper: 'Cropper/Cropper',
    tagsInput: 'tagsInput/tagsInput',
    citypicker: 'city-picker/city-picker',
    introJs: 'introJs/introJs',
    zTree: 'zTree/zTree'
}).use(['layer', 'admin','notice'], function () {
    var $ = layui.jquery;
    var layer = layui.layer;
    var admin = layui.admin;
    var notice = layui.notice;
    // 移除loading动画
    setTimeout(function () {
        admin.removeLoading();
    }, window === top ? 300 : 0);
    admin.events.clear = function(){
        layer.confirm('你确定要清除缓存吗？', {icon: 3, title:'提示'}, function(index){
            $.getJSON('admin/index/clear',function(res){
                if(res.code == 200){
                    //notice.msg('成功', {icon: 1});
                    wk.success(res.msg);
                }else if(res.code == 100){
                    wk.error(res.msg);
                }
            });
        })
    }
});

// 获取当前项目的根路径，通过获取layui.js全路径截取assets之前的地址
function getProjectUrl() {
    var layuiDir = layui.cache.dir;
    if (!layuiDir) {
        var js = document.scripts, last = js.length - 1, src;
        for (var i = last; i > 0; i--) {
            if (js[i].readyState === 'interactive') {
                src = js[i].src;
                break;
            }
        }
        var jsPath = src || js[last].src;
        layuiDir = jsPath.substring(0, jsPath.lastIndexOf('/') + 1);
    }
    return layuiDir.substring(0, layuiDir.indexOf('assets'));
}
// layui.use(['layer'], function () {
// //wk
// var wk = {
//     /*成功弹出层*/
//     //message 提示信息
//     //code js代码弹出层关闭后执行
//     //url 跳转地址
//     //ico 提示样式图标 0:感叹号 1:对号  2:叉号 3:问号 4:锁 5:哭脸 6:笑脸 16:加载
//     success: function(message,code,url,ico){
//         var ico = ico ? ico : 1//设置参数的默认值为1
//             , url = url ? url : ''
//             , code = code ? code : '';
//         layer.msg(message, {icon: ico,time:1500, shade: 0.1}, function(index){
//             layer.close(index);
//             if(url != ""){
//                 window.location.href=url;
//             }
//             if(code != ''){
//                 new Function(code)();
//             }
//         });
//     },
//     /*错误弹出层*/
//     //message 提示信息
//     //code js代码弹出层关闭后执行
//     //url 跳转地址
//     //ico 提示样式图标 0:感叹号 1:对号  2:叉号 3:问号 4:锁 5:哭脸 6:笑脸 16:加载
//     error: function(message,code,url,ico) {
//         var ico = ico ? ico : 2//设置参数的默认值为2
//             , url = url ? url : ''
//             , code = code ? code : '';
//         layer.msg(message, {icon: ico,time:1500, shade: 0.1}, function(index){
//             layer.close(index);
//             if(url != ""){
//                 window.location.href=url;
//             }
//             if(code != ''){
//                 new Function(code)();
//             }
//         });
//     },
//     /*提示信息弹出层*/
//     //message 信息内容
//     //code js代码弹出层关闭后执行
//     //btn 确认按钮内容
//     //offset 坐标
//     //btnAlign 按钮坐标
//     //shade 阴影层
//     msg: function(message,code,btn,offset,btnAlign,shade){
//         var btn = btn ? btn : "确认"
//             , offset = offset ? offset : 'auto'
//             , code = code ? code : ''
//             , btnAlign = btnAlign ? btnAlign : 'c'
//             , shade = shade ? shade : 0.3;
//         var index = layer.open({
//             type: 1
//             ,offset: offset
//             ,content: '<div style="width:250px;text-align:center;padding: 20px 30px;">' + message + '</div>'
//             ,btn: btn
//             ,btnAlign: btnAlign
//             ,shade: shade //不显示遮罩
//             ,yes: function(){
//                 layer.close(index);
//                 if(code != ''){
//                     new Function(code)();
//                 }
//             }
//         });
//     },
//     /*确认弹出层*/
//     //id 操作记录id
//     //url 地址
//     //msg 提示信息
//     confirm : function(id,url,msg) {
//         var msg = msg ? msg : '确认删除此条记录吗?';
//         layer.confirm(msg, {icon: 3, title:'提示'}, function(index){
//             $.ajax({
//                 url: url,
//                 type: "post",
//                 dataType: "json",
//                 data: "id=" + id,
//                 success: function (res) {
//                     if(res.code == 200){
//                         layer.msg(res.msg,{icon:1,time:1500,shade: 0.1},function(){
//                             //重载当前页表格
//                             $(".layui-laypage-btn").click();
//                         });
//                     } else if (res.code == 100){
//                         layer.msg(res.msg,{icon:2,time:1500,shade: 0.1});
//                     }
//                 }
//             });
//             layer.close(index);
//         })
//     },
//     /*状态*/
//     //id 操作记录id
//     //num 修改的值
//     //url 地址
//     //obj dom元素
//     status:function (id,num,url,obj)
//     {
//         layui.use('form', function () {
//             var form = layui.form;
//             $.ajax({
//                 url: url,
//                 type: "post",
//                 dataType: "json",
//                 data: "id=" + id + '&num=' + num,
//                 success: function (res) {
//                     var e = obj.elem.checked;
//                     if (res.code == 200 ) {
//                         layer.msg(res.msg, {icon: 1, time: 1500, shade: 0.1});
//                         return false;
//                     } else if (res.code == 100 ) {
//                         // if("undefined" != typeof(res.type)){
//                         //     $('#zt' + id ).next('div').addClass('layui-form-onswitch');
//                         //     $('#zt' + id ).next('div').children("em").html('ON');
//                         // }else{
//                         //     if($('#zh' + id ).val()==2){
//                         //         $('#zt' + id ).next('div').removeClass('layui-form-onswitch');
//                         //         $('#zt' + id ).next('div').children("em").html('OFF');
//                         //     }else{
//                         //         $('#zt' + id ).next('div').addClass('layui-form-onswitch');
//                         //         $('#zt' + id ).next('div').children("em").html('ON');
//                         //     }
//                         // }
//                         obj.elem.checked = !e;
//                         form.render();
//                         layer.msg(res.msg, {icon: 2, time: 1500, shade: 0.1});
//                         return false;
//                     } else if (res.code == 0) {
//                         obj.elem.checked = !e;
//                         form.render();
//                         // if($('#zh' + id ).val()==2){
//                         //     $('#zt' + id ).next('div').removeClass('layui-form-onswitch');
//                         //     $('#zt' + id ).next('div').children("em").html('OFF');
//                         // }else{
//                         //     $('#zt' + id ).next('div').addClass('layui-form-onswitch');
//                         //     $('#zt' + id ).next('div').children("em").html('ON');
//                         // }
//                         // layer.msg(res.msg, {icon: 7, time: 1500, shade: 0.1});
//                         // return false;
//                     }
//                 }
//             });
//         });
//     },
//     /*批量删除*/
//     //ids 选中的id
//     //url 地址
//     batchDel : function(ids,url)
//     {
//         layer.confirm('确认批量删除记录吗?', {icon: 3, title:'提示'}, function(index){
//             $.ajax({
//                 url: url,
//                 type: "post",
//                 dataType: "json",
//                 data: 'ids=' + ids,
//                 success: function (res) {
//                     if(res.code == 200){
//                         layer.msg(res.msg,{icon:1,time:1500,shade: 0.1},function(){
//                             //重载当前页表格
//                             $(".layui-laypage-btn").click();
//                         });
//                     } else if (res.code == 100){
//                         layer.msg(res.msg,{icon:2,time:1500,shade: 0.1});
//                     }
//                 }
//             });
//             layer.close(index);
//         })
//     },
//     /*批量禁用*/
//     //ids 选中的id
//     //num 修改的值
//     //url 地址
//     batchForbidden : function(id,num,url)
//     {
//         if(id == ''){
//             var msg = '确认禁用全部记录吗?';
//         }else{
//             var msg = '确认批量禁用记录吗?';
//         }
//         layer.confirm(msg, {icon: 3, title:'提示'}, function(index){
//             $.ajax({
//                 url: url,
//                 type: "post",
//                 dataType: "json",
//                 data: "ids=" + id + '&num=' + num,
//                 success: function (res) {
//                     if(res.code == 200){
//                         layer.msg(res.msg,{icon:1,time:1500,shade: 0.1},function(){
//                             //重载当前页表格
//                             $(".layui-laypage-btn").click();
//                         });
//                     } else if (res.code == 100){
//                         layer.msg(res.msg,{icon:2,time:1500,shade: 0.1});
//                     }
//                 }
//             });
//             layer.close(index);
//         })
//     },
//     /*批量启用*/
//     //ids 选中的id
//     //num 修改的值
//     //url 地址
//     usingAll : function(id,num,url)
//     {
//         if(id == ''){
//             var msg = '确认启用全部记录吗?';
//         }else{
//             var msg = '确认批量启用记录吗?';
//         }
//         layer.confirm(msg, {icon: 3, title:'提示'}, function(index){
//             $.ajax({
//                 url: url,
//                 type: "post",
//                 dataType: "json",
//                 data: "ids=" + id + '&num=' + num,
//                 success: function (res) {
//                     if(res.code == 200){
//                         layer.msg(res.msg,{icon:1,time:1500,shade: 0.1},function(){
//                             //重载当前页表格
//                             $(".layui-laypage-btn").click();
//                         });
//                     } else if(res.code == 100){
//                         layer.msg(res.msg,{icon:2,time:1500,shade: 0.1});
//                     }
//                 }
//             });
//             layer.close(index);
//         })
//     },
//     /*excel导出*/
//     //id 选中的id
//     //map 搜索条件
//     //url 地址
//     excelAll : function(id,map,url)
//     {
//         layer.confirm('确认导出数据吗？', {icon: 3, title:'提示'}, function(index){
//             //加载层
//             var loading = layer.msg('正在处理数据，请勿关闭页面...', {icon: 16,shade: 0.4,time:false});
//             $.ajax({
//                 url: url,
//                 type: "post",
//                 dataType: "json",
//                 data: "ids=" + id + '&' + map,
//                 success: function (res) {
//                     if(res.code == 200){
//                         //关闭加载层
//                         layer.close(loading);
//                         location.href = (res.msg);
//                         // layui.table.exportFile(res.cellname, res.data , 'xlsx'); //默认导出 csv，也可以为：xls
//                     } else if(res.code == 100){
//                         layer.msg(res.msg,{icon:2,time:1500,shade: 0.1});
//                     }
//                 }
//             });
//             layer.close(index);
//         })
//     },
//     //实时刷新数据
//     refresh:function(obj,type,url){
//         $.ajax({
//             url:url,
//             type:'post',
//             dataType:'json',
//             data:'type=' + type,
//             success:function(res){
//                 if(res.code == 200){
//                     $('#'+obj).html(res.msg);
//                 }
//             }
//         })
//     },
//     /*快捷修改*/
//     //id 记录id
//     //url 地址
//     //field 修改字段名
//     //value 修改后的值
//     change:function(id,url,field,value,table){
//         $.ajax({
//             url:url,
//             type:'post',
//             dateType:'json',
//             data:'id='+id +'&field='+field + '&value='+ value + '&table='+ table,
//             success :function(res){
//                 if(res.code == 200){
//                     layer.msg(res.msg,{icon:1,time:1500,shade: 0.1},function(){
//                         //重载当前页表格
//                         $(".layui-laypage-btn").click();
//                         layui.table.reload('LAY-table');
//                     });
//                 }else if(res.code == 100){
//                     layer.msg(res.msg,{icon:2,time:1500,shade: 0.1},function(){
//                         //重载当前页表格
//                         $(".layui-laypage-btn").click();
//                         layui.table.reload('LAY-table');
//                     });
//                 }else if(res.code == 0){
//                     //重载当前页表格
//                     $(".layui-laypage-btn").click();
//                     layui.table.reload('LAY-table');
//                 }
//             }
//         });
//     },
//     /*单张图片上传*/
//     //param json数组
//     //url上传接口
//     //size限制图片大小
//     //num 创建多个图片上传时用 1、2、3....
//     //type 限制图片格式 'jpg,png'
//     //domain 上传至CDN的域名
//     uploadImg:function(param){
//         var ttt = param.num ? param.num : ''//按钮type
//             , domain = param.domain ? param.domain : ''
//             , url = param.url ? param.url : "/admin/Upload/upload"
//         if(param.type && param.type != ''){
//             var x = param.type.split(',');
//             var kkk = '';
//             $.each(x,function (index,item) {
//                 kkk += '.'+item+',';
//             })
//         }else{
//             var kkk = '.jpg,.jpeg,.png'
//         }
//         kkk = kkk.substring(0, kkk.lastIndexOf(','));
//         var zzz = param.type ? param.type : 'jpg,jpeg,png';
//         var uploaderImg = WebUploader.create({
//             auto: true,// 选完文件后，是否自动上传。
//             swf: '/static/admin/js/webupload/Uploader.swf',// swf文件路径
//             server: url,// 文件接收服务端。
//             duplicate :true,// 重复上传图片，true为可重复false为不可重复
//             pick: {
//                 id: "#lay-upload"+ttt,// 选择文件的按钮
//                 multiple: false,//true多图上传 false单图上传
//                 label: "选择图片"
//             },
//             fileSingleSizeLimit: param.size*1024*1024, //限制上传单张图片文件大小，单位是B，1M=1024000B
//             accept: {
//                 title: 'Images',
//                 extensions: zzz,
//                 mimeTypes: kkk
//             },
//             //上传成功
//             'onUploadSuccess': function(file, data, response) {
//                 $("#lay-img"+ttt).val(data._raw);
//                 $("#" + file.id).attr('src',domain+ data._raw).show();
//                 $( '#'+file.id ).next('p').html('<span style="color: #009688;">上传成功!</span>');
//                 $('#'+file.id).viewer({
//                     url: 'data-original',
//                 });
//             },
//             //上传失败
//             'uploadError':function(file){
//                 $( '#'+file.id ).next('p').html('<span style="color: #FF5722;">上传失败!</span>');
//             }
//         });
//         //图片加入队列
//         uploaderImg.on( 'fileQueued', function( file ) {
//             $('#lay-img-list'+ttt).html('<img class="layui-append-img" style="display: none;" id="' + file.id + '" title="' + file.name + '"  alt="' + file.name + '" ><p id="lay-msg">正在上传... <i class="layui-icon layui-icon-loading-1 layui-icon layui-anim layui-anim-rotate layui-anim-loop"></i></p>')
//         });
//         //错误信息提示
//         uploaderImg.on('error', function (code) {
//             switch (code) {
//                 case 'F_EXCEED_SIZE':
//                     layer.msg('图片大小不得超过'+  uploaderImg.options.fileSingleSizeLimit/1024/1024 + 'MB',{icon:2,time:1500,shade:0.1});
//                     break;
//                 case 'Q_TYPE_DENIED':
//                     layer.msg('请上传正确的图片格式',{icon:2,time:1500,shade:0.1});
//                     break;
//                 default:
//                     layer.msg('上传错误，请刷新',{icon:2,time:1500,shade:0.1});
//                     break;
//             }
//         });
//     },
//     /*音频上传*/
//     //param json数组
//     //url上传接口 必传
//     //size限制音频大小 必传
//     //num 创建多个音频上传时用 1、2、3....
//     //type 限制音频格式 'mp3,wav'
//     uploadMusic:function(param){
//         var t = param.num ? param.num : ''//按钮type
//             , domain = param.domain ? param.domain : ''
//             , url = param.url ? param.url : "/admin/Upload/upload"
//         if(param.type && param.type != ''){
//             var x = param.type.split(',');
//             var k = '';
//             $.each(x,function (index,item) {
//                 k += '.'+item+',';
//             })
//         }else{
//             var k = '.mp3'
//         }
//         k = k.substring(0, k.lastIndexOf(','));
//         var z = param.type ? param.type : 'mp3';
//         var uploaderMusic = WebUploader.create({
//             auto: true,// 选完文件后，是否自动上传。
//             swf: '/static/admin/js/webupload/Uploader.swf',// swf文件路径
//             server: url,// 文件接收服务端。
//             duplicate :true,// 重复上传文件，true为可重复false为不可重复
//             pick: {
//                 id: "#lay-music-upload" + t,// 选择文件的按钮
//                 multiple: false,//true多文件上传 false单文件上传
//                 label: "选择音频"
//             },
//             fileSingleSizeLimit: param.size*1024*1024, //限制上传单个文件大小，单位是B，1M=1024000B
//             accept: {
//                 title: 'Music',
//                 extensions: z,
//                 mimeTypes: k
//             },
//             //上传成功
//             'onUploadSuccess': function(file, data, response) {
//                 $("#lay-music"+t).val(data._raw);
//                 $("#" + file.id).show();
//                 /**************音频播放器***************/
//                 var wxAudio = new WxAudio({
//                     ele: '#'+ file.id,//dom元素
//                     title: file.name,//文件名
//                     disc: '',//描述
//                     src: domain+data._raw,//音频地址
//                     width: '320px',//进度条
//                     loop: false,//循环播放 bool值
//                     currentTime:0,//从多少秒开始播放
//                     ended: function () {}//播放完执行，loop为true不执行
//                 });
//                 /**************音频播放器***************/
//                 $( '#'+file.id ).next('p').html('<span style="color: #009688;">上传成功!</span>');
//             },
//             //上传失败
//             'uploadError':function(file){
//                 $( '#'+file.id ).next('p').html('<span style="color: #FF5722;">上传失败!</span>');
//             }
//         });
//         //音频加入队列
//         uploaderMusic.on( 'fileQueued', function( file ) {
//             $('#lay-music-list'+t).html('<div id="' + file.id + '" style="margin: 0 10px 10px 0;display:none;"></div><p id="lay-msg">正在上传... <i class="layui-icon layui-icon-loading-1 layui-icon layui-anim layui-anim-rotate layui-anim-loop"></i></p>')
//         });
//         //错误信息提示
//         uploaderMusic.on('error', function (code) {
//             switch (code) {
//                 case 'F_EXCEED_SIZE':
//                     layer.msg('音频大小不得超过'+  uploaderMusic.options.fileSingleSizeLimit/1024/1024 + 'MB',{icon:2,time:1500,shade:0.1});
//                     break;
//                 case 'Q_TYPE_DENIED':
//                     layer.msg('请上传正确的音频格式',{icon:2,time:1500,shade:0.1});
//                     break;
//                 default:
//                     layer.msg('上传错误，请刷新',{icon:2,time:1500,shade:0.1});
//                     break;
//             }
//         });
//     },
//     /*跳转页面*/
//     //url地址
//     href:function(url){
//         $.get(url, {}, function(res){
//             if(res.code != 0){
//                 window.location.href=url;
//             }
//         })
//     },
//     /*iframe页面弹出层*/
//     //title 标题
//     //url 请求页面地址
//     //w 宽度
//     //f 最大化
//     layer_show:function(title,url,w,f){
//         if (w == null || w == '') {
//             w =($(window).width() - 200)
//         };
//         $.get(url, {}, function(res){
//             if(res.code != 0){
//                 var index = layer.open({
//                     anim: 0,//css动画 0-6
//                     isOutAnim:true,
//                     type: 2,
//                     area: w+'px',
//                     // btn: ['保存', '关闭'],
//                     fix: false, //不固定
//                     maxmin: true,//最大最小化
//                     shadeClose:true,//点击遮罩层关闭
//                     scrollbar: false,//屏蔽浏览器滚动条
//                     shade:0.4,
//                     title: title,
//                     content: url,
//                     success:function(layero, index){
//                         if(f == null || f == ''){
//                             layer.iframeAuto(index);
//                         }
//                     }
//                 });
//                 //开启最大化
//                 if(f != null && f != ''){
//                     layer.full(index);
//                 }
//             }
//         });
//     },
//     /*模态框*/
//     //title 标题
//     //content 内容
//     //w 宽度
//     //h 高度
//     layer_show1:function(title,content,w,h){
//         if (w == null || w == '') {
//             w =($(window).width() - 50)
//         };
//         if (h == null || h == '') {
//             h=($(window).height() - 50);
//         };
//         layer.open({
//             type: 1,
//             anim: 0,//css动画 0-6
//             isOutAnim:true,
//             fix: false, //不固定
//             maxmin: false,//最大最小化
//             scrollbar: false,//屏蔽浏览器滚动条
//             shadeClose:true,//点击遮罩层关闭
//             area:[w+'px', h +'px'],
//             title:title,
//             content: content,
//         });
//     },
//     /*关闭弹出框口*/
//     layer_close:function(type){
//         //type为close关闭弹出层同时刷新表格
//         if(type != "close"){
//             parent.layui.table.reload('LAY-table');
//         }
//         var index = parent.layer.getFrameIndex(window.name);
//         parent.layer.close(index);
//     },
//     /*wangEditor富文本编辑器*/
//     //param json数组
//     //elem dom元素id  必传
//     //url 上传接口
//     //size 图片大小限制
//     //num 一次上传数量限制
//     //menu 菜单配置
//     //style 是否过滤样式 true过滤 false不过滤
//     //filter 是否过滤图片 true过滤 false不过滤
//     wang:function(param){
//         var menu = [
//             'head',  // 标题
//             'bold',  // 粗体
//             'fontSize',  // 字号
//             'fontName',  // 字体
//             'italic',  // 斜体
//             'underline',  // 下划线
//             'strikeThrough',  // 删除线
//             'foreColor',  // 文字颜色
//             'backColor',  // 背景颜色
//             'link',  // 插入链接
//             'list',  // 列表
//             'justify',  // 对齐方式
//             'quote',  // 引用
//             'emoticon',  // 表情
//             'image',  // 插入图片
//             'table',  // 表格
//             'video',  // 插入视频
//             'code',  // 插入代码
//             'undo',  // 撤销
//             'redo'  // 重复
//         ];
//         var url = param.url ? param.url : '/admin/Upload/wangUpload',
//             size = param.size ? param.size : 5,
//             num = param.num ? param.num : 3,
//             menu = param.menu ? param.menu : menu,
//             style = param.style ? param.style : true,
//             filter = param.filter ? param.filter : false;
//         var E = window.wangEditor;
//         var editor = new E(param.elem);
//         // 自定义菜单配置
//         editor.customConfig.menus = menu ;
//         editor.customConfig.uploadImgServer = url ; // 上传图片到服务器
//         editor.customConfig.uploadImgMaxSize = size * 1024 * 1024 ; // 将图片大小限制为 5M
//         editor.customConfig.uploadImgMaxLength = num ; // 限制一次最多上传 3张图片
//         editor.customConfig.pasteFilterStyle = style ;// 关闭粘贴样式的过滤
//         editor.customConfig.pasteIgnoreImg = filter ;// 忽略粘贴内容中的图片
//         editor.customConfig.zIndex = 100 ; //配置编辑区域的 z-index 默认 10000
//         editor.create();
//         return editor;
//     },
//     /*layui富文本编辑器*/
//     layeditor:function(id,url,h,t){
//         var tool = [
//             'strong' //加粗
//             ,'italic' //斜体
//             ,'underline' //下划线
//             ,'del' //删除线
//
//             ,'|' //分割线
//
//             ,'left' //左对齐
//             ,'center' //居中对齐
//             ,'right' //右对齐
//             ,'link' //超链接
//             ,'unlink' //清除链接
//             ,'face' //表情
//             ,'image' //插入图片
//         ];
//         var t = t ? t : tool;
//         var url = url ? url : '/admin/Upload/layUpload';
//         var h = h ? h : 400;
//         layui.use(['layedit'],function(){
//             var layedit = layui.layedit;
//             layedit.set({
//                 uploadImage: {
//                     url: url,
//                     type: 'post'
//                 }
//             });
//             var index = layedit.build(id, {height: h,tool:t});
//         })
//     },
//     /*layui日期选择器*/
//     //obj dom元素
//     lay_date:function(obj){
//         layui.use(['laydate'], function () {
//             var laydate = layui.laydate;
//             laydate.render({
//                 elem: obj
//                 ,type: 'datetime'
//                 ,show: true
//                 ,format:'yyyy-MM-dd HH:mm:ss'
//             });
//         })
//     },
//     /*音频播放组件*/
//     //param json数组
//     //elem dom元素id 必传
//     //src 链接地址 必传
//     //name 文件名
//     //bool 循环播放
//     //time 从多少秒开始播放
//     //disc 描述
//     //width 进度条宽度 px
//     //value 播放完执行的代码，loop为true不执行
//     lay_audio:function (param){
//         var disc = param.disc ? param.disc : '',
//             bool = param.bool ? param.bool : false,
//             time = param.time ? param.time : 0,
//             width = param.width ? param.width : '320px',
//             value = param.value ? param.value : '',
//             name = param.name ? param.name : '';
//         var wxAudio = new WxAudio({
//             ele: param.elem,//dom元素
//             title: name,//文件名
//             disc: disc,//描述
//             src: param.src ,//音频地址
//             width: width,//进度条
//             loop: bool,//循环播放 bool值
//             currentTime:time,//从多少秒开始播放
//             ended: function () {
//                 if(value != ''){
//                     new Function(value)()
//                 }
//             }//播放完执行，loop为true不执行
//         });
//         return wxAudio;
//         // 实例化的wxAudio可以这样操作
//         // wxAudio.audioPlay()   // 播放
//         // wxAudio.audioPause()   // 暂停
//         // wxAudio.audioPlayPause()  // 播放暂停
//         // wxAudio.showLoading(bool)  //显示加载状态  参数bool
//         // wxAudio.audioCut(src,title,disc)  //实现音频切换的效果
//     },
//     /*多图上传*/
//     //param json数组
//     //num 限制上传数量
//     //size 限制上传大小
//     //picker 选择文件按钮
//     //goPicker 继续选择文件按钮
//     //photo 存储上传完成地址input框
//     //url 上传接口
//     //type 限制上传图片格式 jpg,png....
//     //uploader 上传区域
//     //attr 创建多个多图上传时用 1、2、3....
//     //status
//     uploads:function(param){
//         var num = param.num ? param.num : 10,
//             size = param.size ? param.szie : 5,
//             attr = param.attr ? param.attr : '',
//             // picker = param.picker ? param.picker1 : '#filePicker',
//             // pickers = param.picker ? param.picker2 : '#goPicker',
//              photo = param.photo ? param.photo : '#photo',
//             url = param.url ? param.url : "/admin/Upload/upload",
//             status = param.status ? param.status : 'upload'
//         // uploader = param.uploader ? param.uploader : "#uploader"
//
//         if(param.type && param.type != ''){
//             var type = param.type.split(',');
//             var types = '';
//             $.each(type,function (index,item) {
//                 types += '.'+item+',';
//             })
//         }else{
//             var types = '.jpg,.jpeg,.png'
//         }
//         types = types.substring(0, types.lastIndexOf(','));
//         var img = param.type ? param.type : 'jpg,jpeg,png';
//
//         var n, o = jQuery,
//             r = o("#uploader" + attr),
//             l = o('<ul class="filelist"></ul>').appendTo(r.find(".queueList")),
//             d = r.find(".statusBar"),
//             p = d.find(".info"),
//             c = r.find(".uploadBtn"),
//             u = r.find(".placeholder"),
//             f = d.find(".layui-progress").hide(),
//             m = 0,
//             h = 0,
//             g = window.devicePixelRatio || 1,
//             v = 110 * g,
//             b = 110 * g,
//             k = "pedding",
//             w = {},
//             imgArr = [],//存储上传路径
//             x = function () {
//                 var e = document.createElement("p").style,
//                     a = "transition" in e || "WebkitTransition" in e || "MozTransition" in e || "msTransition" in e || "OTransition" in e;
//                 return e = null, a
//             }();
//         if (!WebUploader.Uploader.support()) throw alert("Web Uploader 不支持您的浏览器！如果你使用的是IE浏览器，请尝试升级 flash 播放器"), new Error("WebUploader does not support the browser you are using.");
//         n = WebUploader.create({
//             auto: false,// 选完文件后，是否自动上传。
//             //配置压缩的图片的选项 false不压缩
//             compress: {
//                 // width: 1600,
//                 // height: 1600,
//                 // quality: 90,// 图片质量，只有type为`image/jpeg`的时候才有效。
//                 // allowMagnify: false,// 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
//                 // crop: false,// 是否允许裁剪。
//                 // preserveHeaders: true,// 是否保留头部meta信息。
//                 // noCompressIfLarger: false,// 如果发现压缩后文件大小比原来还大，则使用原来图片 // 此属性可能会影响图片自动纠正功能
//                 compressSize : 10*1024*1024,//单位字节，如果图片大小小于此值，不会采用压缩
//             },
//             //配置生成缩略图的选项。
//             // thumb:{
//             //     width: 110,
//             //     height: 110,
//             //     quality: 100, // 图片质量，只有type为`image/jpeg`的时候才有效。
//             //     allowMagnify: false,// 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
//             //     crop: true, // 是否允许裁剪。
//             //     type: 'image/jpeg'// 为空的话则保留原有图片格式// 否则强制转换成指定的类型。
//             // },
//             pick: {
//                 id: "#filePicker"+ attr,// 选择文件的按钮
//                 multiple: true,//true多图上传 false单图上传
//                 label: "点击选择图片"
//             },
//             prepareNextFile:true,
//             dnd: "#uploader" + attr,
//             duplicate :true,// 重复上传图片，true为可重复false为不可重复
//             paste: document.body,
//             accept: {
//                 title: "Images",
//                 extensions: img,//允许的文件后缀
//                 mimeTypes: types//允许的文件后缀
//             },
//             swf: "/static/admin/webupload/Uploader.swf",// swf文件路径
//             disableGlobalDnd: !0,//拖拽功能
//             chunked: 0,// 分片上传大文件
//             // chunkRetry: 10, // 如果某个分片由于网络问题出错，允许自动重传多少次？
//             // thread: 100,// 最大上传并发数
//             // method: 'POST',
//             // formData:{guid:'qweqwe'},
//             server: url,// 文件接收服务端。
//             fileNumLimit: num,//限制上传个数
//             // fileSizeLimit: 100* 1024 * 1024,// 总上传限制大小
//             fileSingleSizeLimit: size*1024*1024, //限制上传单张图片文件大小，单位是B，1M=1024000B
//         }), n.addButton({
//             id: '#goPicker' + attr,
//             multiple: true,//true多图上传 false单图上传
//             label: "继续添加"
//         }), n.onUploadProgress = function (e, a) {
//             var i = o("#" + e.id),
//                 t = i.find(".progress span");
//             t.css("width", 100 * a + "%"), w[e.id][1] = a, s()
//         }, n.onFileQueued = function (a) { //文件加入队列触发
//             m++, h += a.size, 1 === m && (u.addClass("element-invisible"), d.show()), e(a), t("ready"), s();
//             // if(m == n.options.fileNumLimit){
//             //     o("#filePicker2").addClass("element-invisible");
//             // }
//             c.removeClass('disabled');
//             /*编辑部分*/
//             if(status == 'update'){
//                 if (m > n.options.fileNumLimit) {
//                     n.removeFile(a, true);
//                     layer.msg( '最多只能上传'+  n.options.fileNumLimit +'张！',{icon:2,shade:0.1,time:2000});
//                     return;
//                 }
//                 // if(m == n.options.fileNumLimit){
//                 //     o("#filePicker2").addClass("element-invisible");
//                 // }
//                 var picList = $('#hid' + attr).val().split(',');
//                 //删除imgArr中的元素
//                 for(var i=0; i<picList.length; i++) {
//                     if(picList[i] == a.name) {
//                         $('#'+a.id).children('div').remove();
//                         $('#'+a.id).append('<span class="success"></span>');
//                         $('#'+a.id).append('<div ><span class="close-upimg" onclick="imgDel'+ attr +'(this,\'#photo'+ attr +'\',\'#hid'+ attr +'\',\'#del'+ attr +'\')" value="'+a.name+'"><i class="fa fa-times-circle"></i></span></div>');
//                         // c.addClass('disabled');
//                     }
//                 }
//             }
//             /*编辑部分*/
//         }, n.onFileDequeued = function (e) {//删除队列文件触发
//             m--, h -= e.size, m || t("pedding"), a(e), s()
//             // if(m < n.options.fileNumLimit){
//             //     o("#filePicker2").removeClass("element-invisible");
//             // }
//         }, n.on("all", function (e) {
//             switch (e) {
//                 case "uploadFinished":
//                     t("confirm");
//                     break;
//                 case "startUpload":
//                     t("uploading");
//                     break;
//                 case "stopUpload":
//                     t("paused")
//             }
//         }),n.on( 'uploadSuccess', function( e,d ) {
//             // imgArr.push(d._raw);//返回的图片地址存入imgArr
//             // console.log(e);
//             var pt = $("#photo" + attr).val();
//             if(pt == ''){
//                 $("#photo" + attr).val(d._raw);
//             }else{
//                 $("#photo" + attr).val(pt + ',' + d._raw);
//             }
//             // $("#photo").val($("#photo").val().split(',').push(d._raw));
//             var a = o("#" + e.id);
//             if(status == 'update'){
//                 a.append('<div ><span class="close-upimg" onclick="imgDel'+ attr +'(this,\'#photo'+ attr +'\',\'#hid'+ attr +'\',\'#del'+ attr +'\')" value="'+d._raw+'"><i class="fa fa-times-circle"></i></span></div>');
//             }else{
//                 //删除图片按钮
//                 a.append('<div ><span class="close-upimg" onclick="imgDel'+ attr +'(this,\'#photo'+ attr +'\')" value="'+d._raw+'"><i class="fa fa-times-circle"></i></span></div>');
//             }
//
//         }), n.onError = function (e) {
//             // alert(e);
//             var err = '';
//             switch (e) {
//                 case 'F_EXCEED_SIZE':
//                     err += '单张图片大小不得超过'+  n.options.fileSingleSizeLimit/1024/1024 + 'MB！';
//                     break;
//                 case 'Q_EXCEED_NUM_LIMIT':
//                     err += '最多只能上传'+  n.options.fileNumLimit +'张！';
//                     break;
//                 case 'Q_EXCEED_SIZE_LIMIT':
//                     err += '上传图片总大小超出'+ n.options.fileSizeLimit/1024/1024 +'MB！';
//                     break;
//                 case 'Q_TYPE_DENIED':
//                     err += '无效图片类型，请上传正确的图片格式！';
//                     break;
//                 case 'F_DUPLICATE':
//                     err += '文件不能重复上传！';
//                     break;
//                 default:
//                     err += '上传错误，请刷新！';
//                     break;
//             }
//             layer.msg( err,{icon:2,shade:0.1,time:2000});
//         }, c.on("click", function () {
//             return o(this).hasClass("disabled") ? !1 : void("ready" === k ? n.upload() : "paused" === k ? n.upload() : "uploading" === k && n.stop(true))
//         }), p.on("click", ".retry", function () {
//             n.retry() //重新上传
//         }), p.on("click", ".ignore", function () {
//             o(picker1).removeClass("element-invisible");
//             //清除上传失败文件
//             $.each(n.getFiles('error'),function(index,item){
//                 // console.log(item);
//                 $('#'+item.id).parents('li').remove();
//                 n.removeFile(item, true);//删除队列的文件
//             })
//         }), c.addClass("state-" + k), s();
//         /*****************回显图片********************/
//         if(status == 'update'){
//             var getFileBlob = function (url, cb) {
//                 var xhr = new XMLHttpRequest();
//                 xhr.open("GET", url);
//                 xhr.responseType = "blob";
//                 xhr.addEventListener('load', function() {
//                     cb(xhr.response);
//                 });
//                 xhr.send();
//             };
//
//             var blobToFile = function (blob, name) {
//                 blob.lastModifiedDate = new Date();
//                 blob.name = name;
//                 return blob;
//             };
//
//             var getFileObject = function(filePathOrUrl, cb) {
//                 getFileBlob(filePathOrUrl, function (blob) {
//                     cb(blobToFile(blob, 'test.jpg'));
//                 });
//             };
//
//             //需要编辑的图片列表
//             if($('#hid' + attr).val() != ''){
//                 var picList = $('#hid' + attr).val().split(',');
//                 $.each(picList, function(index,item){
//                     getFileObject(item, function (fileObject) {
//                         var wuFile = new WebUploader.Lib.File(WebUploader.guid('rt_'),fileObject);
//                         var file = new WebUploader.File(wuFile);
//                         file.setStatus(status.COMPLETE);//加入队列设置状态完成上传
//                         file.name = item;
//                         n.addFiles(file)
//                         // alert(n.getStats().successNum)
//                     })
//                 });
//             }
//         }
//         /*****************回显图片********************/
//         function e(e) {
//             var a = o('<li id="' + e.id + '"><p class="title">' + e.name + '</p><p class="imgWrap"></p><p class="progress"><span></span></p></li>'),
//                 s = o('<div class="file-panel"><span class="cancel">删除</span><span class="rotateRight">向右旋转</span><span class="rotateLeft">向左旋转</span></div>').appendTo(a),
//                 i = a.find("p.progress span"),
//                 t = a.find("p.imgWrap"),
//                 r = o('<p class="error"></p>'),
//                 d = function (e) {
//                     switch (e) {
//                         case 'Q_EXCEED_NUM_LIMIT':
//                             text= '最多只能上传10张！';
//                             break;
//                         case "exceed_size":
//                             text = "文件大小超出";
//                             break;
//                         case "interrupt":
//                             text = "上传暂停";
//                             break;
//                         default:
//                             text = "上传失败，请重试"
//                     }
//                     r.text(text).appendTo(a)
//                 };
//             "invalid" === e.getStatus() ? d(e.statusText) : (t.text("预览中"), n.makeThumb(e, function (e, a) {
//                 if (e) return void t.text("不能预览");
//                 var s = o('<img src="' + a + '" >');
//                 t.empty().append(s)
//             }, v, b), w[e.id] = [e.size, 0], e.rotation = 0), e.on("statuschange", function (t, n) {
//                 "progress" === n ? i.hide().width(0) : "queued" === n && (a.off("mouseenter mouseleave"), s.remove()), "error" === t || "invalid" === t ? (console.log(e.statusText), d(e.statusText), w[e.id][1] = 1) : "interrupt" === t ? d("interrupt") : "queued" === t ? w[e.id][1] = 0 : "progress" === t ? (r.remove(), i.css("display", "block")) : "complete" === t && a.append('<span class="success"></span>'), a.removeClass("state-" + n).addClass("state-" + t)
//             }), a.on("mouseenter", function () {
//                 s.stop().animate({
//                     height: 30
//                 })
//             }), a.on("mouseleave", function () {
//                 s.stop().animate({
//                     height: 0
//                 })
//             }), s.on("click", "span", function () {
//                 var a, s = o(this).index();
//                 switch (s) {
//                     case 0:
//                         return void n.removeFile(e);
//                     case 1:
//                         e.rotation += 90;
//                         break;
//                     case 2:
//                         e.rotation -= 90
//                 }
//                 x ? (a = "rotate(" + e.rotation + "deg)", t.css({
//                     "-webkit-transform": a,
//                     "-mos-transform": a,
//                     "-o-transform": a,
//                     transform: a
//                 })) : t.css("filter", "progid:DXImageTransform.Microsoft.BasicImage(rotation=" + ~~(e.rotation / 90 % 4 + 4) % 4 + ")")
//             }), a.appendTo(l)
//         }
//         function a(e) {
//             var a = o("#" + e.id);
//             delete w[e.id], s(), a.off().find(".file-panel").off().end().remove()
//         }
//         function s() {
//             var e, a = 0,
//                 s = 0,
//                 t = f.children();
//             o.each(w, function (e, i) {
//                 s += i[0], a += i[0] * i[1]
//             }), e = s ? a / s : 0, t.eq(0).children().eq(0).text(Math.round(100 * e) + "%"), t.eq(0).css("width", Math.round(100 * e) + "%"), i()
//         }
//         function i() {
//             var e, a = "";
//             "ready" === k ? a = "选中" + m + "张图片，共" + WebUploader.formatSize(h) + "。" : "confirm" === k ? (e = n.getStats(), e.uploadFailNum && (a = "已成功上传" + e.successNum + "张照片，" + e.uploadFailNum + '张照片上传失败，<a class="retry" href="#">重新上传</a>失败图片或<a class="ignore" href="#">忽略</a>')) : (e = n.getStats(), a = "共" + m + "张（" + WebUploader.formatSize(h) + "），已上传" + e.successNum + "张", e.uploadFailNum && (a += "，失败" + e.uploadFailNum + "张")), p.html(a)
//         }
//         function t(e) {
//             var a;
//             if (e !== k) {
//                 switch (c.removeClass("state-" + k), c.addClass("state-" + e), k = e) {
//                     case "pedding":
//                         u.removeClass("element-invisible"), l.parent().removeClass("filled"), l.hide(), d.addClass("element-invisible"), n.refresh();
//                         break;
//                     case "ready":
//                         o("#filePicker2").removeClass("element-invisible"), l.parent().addClass("filled"), l.show(), d.removeClass("element-invisible"), n.refresh();
//                         break;
//                     case "uploading":
//                         o("#filePicker2").addClass("element-invisible"), f.show(), c.text("暂停上传");
//                         break;
//                     case "paused":
//                         f.show(), c.text("继续上传");
//                         break;
//                     case "confirm":
//                         if (f.hide(), c.text("开始上传"), a = n.getStats(), a.successNum && !a.uploadFailNum) return void t("finish");
//                         break;
//                     case "finish":
//                         // if(n.getStats().successNum < n.options.fileNumLimit){
//                         o("#filePicker2").removeClass("element-invisible");
//                         // }
//                         // if(n.getStats().successNum == n.options.fileNumLimit){
//                         c.addClass('disabled');
//                         // }
//                         a = n.getStats(), a.successNum ? layer.msg('上传成功',{icon:1,shade:0.1,time:2000}) : (k = "done", location.reload())
//                 }
//                 i()
//             }
//         }
//         //重置图片选择按钮，防止出现错误
//         n.refresh();
//         return n;
//     },
//     /*多图上传删除图片*/
//     //e dom元素
//     //obj input框元素
//     //n webUploader初始化返回值
//     //url 删除图片接口
//     uploads_del:function(e,obj,n,url){
//         var url = url ? url : "/admin/Upload/deleteImg"
//         layer.confirm('确认删除此图片吗?', {icon: 3, title:'提示'}, function(index) {
//             var add = $(e).attr('value');
//             var id = $(e).parents('li').attr('id');
//             var imgs = $(obj).val().split(',')
//             for (var i = 0; i < imgs.length; i++) {
//                 if (imgs[i] == add) {
//                     imgs.splice(i, 1);
//                     break;
//                 }
//             }
//             $(obj).val(imgs);
//             $.ajax({
//                 url:url,
//                 data: 'add=' + add,
//                 type: 'post',
//                 dataType: 'json',
//                 success: function (res) {
//                     if (res.code == 200) {
//                         $(e).parents('li').remove();
//                         n.removeFile(id, true);//删除队列的文件
//                         // console.log(n.getFiles().length);
//                     } else {
//                         layer.msg('删除失败，请重试...', {icon: 2, shade: 0.1, time: 2000})
//                     }
//                 }
//             })
//             layer.close(index);
//         });
//     },
//     /*多图上传修改删除图片*/
//     //e dom元素
//     //obj input框元素
//     //hid 存储回显图片地址dom
//     //del 存储删除的回显图片地址dom
//     //n webUploader初始化返回值
//     //url 删除图片接口
//     update_del:function(e,obj,hid,del,n,url){
//         var url = url ? url : "/admin/Upload/deleteImg"
//         layer.confirm('确认删除此图片吗?', {icon: 3, title:'提示'}, function(index) {
//             // alert( $("#photo").val());
//             var add = $(e).attr('value');
//             var id = $(e).parents('li').attr('id');
//             var imgs = $(obj).val().split(',')
//             var pic = $(hid).val().split(',');
//             //删除imgArr中的元素
//             for (var i = 0; i < imgs.length; i++) {
//                 if (imgs[i] == add) {
//                     imgs.splice(i, 1);
//                     break;
//                 }
//             }
//             $(obj).val(imgs);
//             var tag = 0;
//             for (var i = 0; i < pic.length; i++) {
//                 if (pic[i] == add) {
//                     if($(del).val() == ''){
//                         $(del).val(add);
//                     }else{
//                         $(del).val($(del).val() + ',' + add);
//                     }
//                     tag = 1;
//                 }
//             }
//             if(tag == 0){
//                 $.ajax({
//                     url: url,
//                     data: 'add=' + add,
//                     type: 'post',
//                     dataType: 'json',
//                     success: function (res) {
//                         if (res.code == 200) {
//                             $(e).parents('li').remove();
//                             n.removeFile(id, true);//删除队列的文件
//                             // console.log(n.getFiles().length);
//                         } else {
//                             layer.msg('删除失败，请重试...', {icon: 2, shade: 0.1, time: 2000})
//                         }
//                     }
//                 })
//             }else{
//                 // alert(imgArr);
//                 $(e).parents('li').remove();
//                 n.removeFile(id, true);//删除队列的文件
//             }
//
//             // alert( $("#photo").val());
//             // console.log(n.getStats().numOfQueue);return;
//             layer.close(index);
//         });
//     },
//     /*表格选择器*/
//     //elem input框dom元素
//     //field 存储的字段 一般为主键id
//     //name 展示的字段
//     //key 搜索框name值
//     //splaceholder 搜索输入框的提示文字
//     //url 数据请求地址
//     //status 状态码 默认220
//     //limits 默认[10, 20, 30, 40, 50]
//     //limit 默认10
//     //loading 加载图标 默认true开启
//     //cols 表格的字段数据展示
//     tableSelect:function(param){
//         layui.config({
//             base: '/src/' //静态资源所在路径
//         }).extend({
//             tableSelect: 'modules/tableSelect'
//         }).use(['tableSelect'], function() {
//             var tableSelect = layui.tableSelect,
//                 iplaceholder = param.iplaceholder ? param.iplaceholder : '请选择',
//                 status = param.status ? param.status : 220,
//                 limits = param.limits ? param.limits : [10, 20, 30, 40, 50],
//                 limit = param.limit ? param.limit : 10,
//                 loading = param.loading ? param.loading : true
//             tableSelect.render({
//                 elem: param.elem,	//定义输入框input对象 必填
//                 checkedKey: param.field, //表格的唯一主键，非常重要，影响到选中状态 必填
//                 checkedName: param.name, //表格选中展示的字段名，非常重要，影响到选中状态 必填
//                 searchKey: param.key,	//搜索输入框的name值 默认keyword
//                 searchPlaceholder: param.splaceholder,	//搜索输入框的提示文字 默认关键词搜索
//                 inputPlaceholder: iplaceholder,	//搜索输入框的提示文字 默认关键词搜索
//                 table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
//                     url:param.url
//                     ,response: {
//                         statusCode: status //成功的状态码，默认：0
//                     }
//                     , limits: limits
//                     , limit: limit
//                     , loading: loading
//                     , cols: [param.cols]
//                 },
//                 done: function (elem, data) {}
//             })
//         })
//     },
//     /*一键复制*/
//     //obj 按钮dom元素
//     //备注：input框必须和按钮为兄弟层级关系，input框为上级，按钮元素为下级
//     //input框不可设置disabled属性，只可设置readonly属性
//     lay_copy: function (obj) {
//         if($(obj).prev().val() == ''){
//             return false;
//         }
//         var clipboard = new Clipboard('.layui-copy')
//             , isFirst = true;
//         if($(obj).prev().attr('id')){
//             var id = $(obj).prev().attr('id');
//         }else{
//             var id = 'foo' + new Date().getTime();
//             $(obj).prev().attr('id',id);
//         }
//         if(!$(obj).attr('data-clipboard-target')){
//             $(obj).attr('data-clipboard-target','#'+id);
//         }
//         clipboard.on('success', function(e) {
//             if(isFirst){
//                 layer.msg('复制成功',{time:1500});
//                 isFirst = false;
//             }
//         });
//         // clipboard.on('error', function(e) {
//         //     console.log(e);
//         // });
//     },
//     /*区域打印*/
//     //globalStyles 是否启用自身样式bool
//     //style 外部样式链接
//     //noPrint 不打印什么元素，支持多个属性逗号连接
//     //prepend 头部添加内容，支持html
//     //append 尾部添加内容，支持html
//     lay_print: function(param){
//         $(param.obj).print({
//             globalStyles : param.globalStyles || true,//是否启用自身样式
//             mediaPrint : false,
//             stylesheet : param.style || null,//外部样式链接
//             iframe : true,
//             noPrintSelector : param.noPrint || ".avoid-this",//不打印什么元素，支持多个属性逗号连接
//             prepend : param.prepend || null,//头部添加内容，支持html
//             append : param.append || null,//尾部添加内容，支持html
//             //打印完成回调
//             deferred : $.Deferred().done(function() {
//                 // console.log('Printing done', arguments);
//             })
//         });
//     },
//     /*声音消息*/
//     //src 音频地址
//     voice_msg :function(src){
//         var src = src || '/static/admin/images/default.mp3';
//         if($('#voice_message').length == 0){
//             $('body').append('<div id="voice_message"></div>');
//         }
//         var wxAudio = wk.lay_audio({elem:'#voice_message',src:src});
//         $('#voice_message').hide();
//         wxAudio.audioPlay();
//     }
// }
// });