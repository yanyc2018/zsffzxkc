//图像上传
function selectImg(file) {
    if (!file.files || !file.files[0]){
        return;
    }
    var filename = file.files[0].name;
    var index1=filename.lastIndexOf(".");
    var index2=filename.length;
    var postf=filename.substring(index1,index2);//后缀名
    var postf = postf.toLowerCase(postf);
    if(postf !== '.jpg' && postf !== ".jpeg" && postf !== ".png"){
        layer.msg("图片格式不符合要求", {icon: 2,time:1500,shade: 0.1});
        $('#chooseImg').val('');
        return false;
    }
    if(file.files[0].size > 2*1024*1024){
        layer.msg("图片大小不得超过2MB", {icon: 2,time:1500,shade: 0.1});
        $('#chooseImg').val('');
        return false;
    }
    var reader = new FileReader();
    reader.onload = function (evt) {
        var replaceSrc = evt.target.result;
        //更换cropper的图片
        $(".word").css("display","none");
        $(".size").css("display","none");
        $('#tailoringImg').cropper('replace', replaceSrc,false);//默认false，适应高度，不失真

    }
    reader.readAsDataURL(file.files[0]);
}
//cropper图片裁剪
$('#tailoringImg').cropper({
    aspectRatio: 1/1,//默认比例
    preview: '.previewImg',//预览视图
    guides: true,  //裁剪框的虚线(九宫格)
    autoCropArea: 0.8,  //0-1之间的数值，定义自动剪裁区域的大小，默认0.8
    movable: true, //是否允许移动图片
    dragCrop: true,  //是否允许移除当前的剪裁框，并通过拖动来新建一个剪裁框区域
    movable: true,  //是否允许移动剪裁框
    resizable: true,  //是否允许改变裁剪框的大小
    zoomable: true,  //是否允许缩放图片大小
    mouseWheelZoom: true,  //是否允许通过鼠标滚轮来缩放图片
    touchDragZoom: true,  //是否允许通过触摸移动来缩放图片
    rotatable: true,  //是否允许旋转图片
    crop: function(e) {
        // 输出结果数据裁剪图像。
    }
});
//放大
$(".zoomIn").on("click",function () {
    $('#tailoringImg').cropper("zoom",.1);
});
//缩小
$(".zoomOut").on("click",function () {
    $('#tailoringImg').cropper("zoom",-.1);
});
//旋转
$(".cropper-rotate-btn").on("click",function () {
    $('#tailoringImg').cropper("rotate", 30);
});
//复位
$(".cropper-reset-btn").on("click",function () {
    $('#tailoringImg').cropper("reset");
});
//换向
var flagX = true;
$(".cropper-scaleX-btn").on("click",function () {
    if(flagX){
        $('#tailoringImg').cropper("scaleX", -1);
        flagX = false;
    }else{
        $('#tailoringImg').cropper("scaleX", 1);
        flagX = true;
    }
    flagX != flagX;
});