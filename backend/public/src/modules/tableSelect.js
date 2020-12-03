layui.define(['table', 'jquery', 'form'], function (exports) {
    "use strict";

    var MOD_NAME = 'tableSelect',
        $ = layui.jquery,
        table = layui.table,
        form = layui.form;
    var tableSelect = function () {
        this.v = '1.1.0';
    };

    /**
    * 初始化表格选择器
    */
    tableSelect.prototype.render = function (opt) {
        var elem = $(opt.elem);
        var tableDone = opt.table.done || function(){};
        //数据缓存
        var checkedData = [];
         elem.hide();
         elem.parent().append('<div class="t-select-box"><p class="table-select-placeholder">'+ opt.inputPlaceholder +'</p></div>');
         var elemBox = elem.next('div'),
             place = elemBox.find('.table-select-placeholder')
        //默认设置
        opt.searchKey = opt.searchKey || 'keyword';
        opt.searchPlaceholder = opt.searchPlaceholder || '关键词搜索';
        opt.checkedKey = opt.checkedKey;
        opt.table.page = opt.table.page || true;
        opt.table.height = opt.table.height || 315;

        //css样式
        var css = '.t-select{display:inline-block;border: 1px solid #5FB878;margin-top:5px;margin-left:5px;margin-right:2px;padding: 2px 5px;background-color: #5FB878;border-radius: 2px;color: #FFF;line-height: 18px;height: 18px;cursor: initial;font-size: 14px;}.t-select-box{width:100%;min-height:38px;line-height:30px;border-width: 1px;border-style: solid;background-color: #fff;border-radius: 2px;border-color: #e6e6e6;cursor:pointer;}.t-select-box:hover{border-color: #C9C9C9;}.layui-table-tips{z-index:66666666!important;}.lay-table-select-close{cursor:pointer;font-size: 13px;padding-left: 4px;}.lay-table-select-close:hover{opacity: 0.5}.table-select-placeholder{display:inline-block;color:#CCCCCC;font-size:15px;margin-left:9px;margin-top:5px;}'
        $('head').append('<style rel="stylesheet">'+css+'</style>');

        //页面打开加载数据
        if(elem.val() != ''){
            first(elem.val());
        }
        function first(obj){
            $.ajax({
                url:opt.table.url,
                data:'id=' + obj,
                type:'post',
                dataType:'json',
                success:function(res){
                    $.each(res.data,function(index,item){
                        var index = $.inArray(item[opt.checkedKey].toString(),elem.val().split(","));
                        if(index >= 0){
                            place.hide();
                            checkedData.push(item)
                            elemBox.append('<span class="t-select" data-value="'+ item[opt.checkedKey] +'" id="tSelect_'+ item[opt.checkedKey] +'">'+ item[opt.checkedName] +'<i class="layui-icon layui-icon-close lay-table-select-close" onclick="event.stopPropagation();var id = $(this).parent().attr(\'data-value\'),idz = $(this).parent().parent().prev().attr(\'value\').split(\',\');for(var g=0;g < idz.length;g++){if(id == idz[g]){idz.splice(g,1);$(this).parent().parent().prev().attr(\'value\',idz);$(this).parent().remove();if($(\'.t-select-box\').find(\'span\').length == 0){$(\'.t-select-box\').find(\'p\').show()}}};"></i></span>')
                        }
                    });
                    checkedData = uniqueObjArray(checkedData, opt.checkedKey);
                }
            })
        }
        //elemBox高度变化，table位置重新定位
        elemBox.resize(function() {
            $('.tableSelect').css({'top':elemBox.offset().top + elemBox.outerHeight()+"px",'left':elemBox.offset().left +"px"});
        })

        elemBox.off('click').on('click', function(e) {
            e.stopPropagation();

            // if($('div.tableSelect').length >= 1){
            //     return false;
            // }
            // console.log(elemBox.offset())
            // console.log(elemBox.outerHeight())
            var t = elemBox.offset().top + elemBox.outerHeight()+"px";
            var l = elemBox.offset().left +"px";
            var tableName = "tableSelect_table_" + new Date().getTime();
            var tableBox = '<div class="tableSelect layui-anim layui-anim-upbit" style="left:'+l+';top:'+t+';border: 1px solid #d2d2d2;background-color: #fff;box-shadow: 0 2px 4px rgba(0,0,0,.12);padding:10px 10px 0 10px;position: absolute;z-index:66666666;margin: 5px 0;border-radius: 2px;min-width:530px;max-width:630px;" >';
                tableBox += '<div class="tableSelectBar">';
                tableBox += '<form class="layui-form" action="" style="display:inline-block;">';
                tableBox += '<input style="display:inline-block;width:190px;height:31px;vertical-align:middle;margin-right:10px;border: 1px solid #C9C9C9;" type="text" name="'+opt.searchKey+'" placeholder="'+opt.searchPlaceholder+'" autocomplete="off" class="layui-input" id="search"><button class="layui-btn layui-btn-sm" lay-submit lay-filter="tableSelect_btn_search" title="搜索" alt="搜索"><i class="layui-icon layui-icon-search"></i></button><button class="layui-btn layui-btn-sm layui-btn-normal tableSelect_btn_search" lay-submit lay-filter="tableSelect_btn_search"  title="重置搜索框" alt="重置搜索框" onclick="$(this).prev().prev().val(\'\');"><i class="layui-icon layui-icon-refresh"></i></button><button type="button" class="layui-btn layui-btn-sm layui-btn-danger tableSelect_btn_select layuiTableSelect"  title="清空选中" alt="清空选中" data-type="clearBox"><i class="layui-icon layui-icon-delete"></i></button>';
                tableBox += '</form>';
                tableBox += '<span style="float:right;padding:5px 10px;" class="layui-badge layui-bg-gray table-selected">已选中<span></span>个</span>';
                tableBox += '</div>';
                tableBox += '<table id="'+tableName+'" lay-filter="'+tableName+'"></table>';
                tableBox += '</div>';
                tableBox = $(tableBox);
            $('body').append(tableBox);
            // $('#search').focus();
            //$('#layTables').attr('value','');$('.t-select-box').html('');
            var ids = [];
            //渲染TABLE
            opt.table.elem = "#"+tableName;
            opt.table.id = tableName;
            opt.table.done = function(res, curr, count){
                defaultChecked(res, curr, count);
                setChecked(res, curr, count);
                tableDone(res, curr, count);
            };
            var tableSelect_table = table.render(opt.table);

            //清空
            var active = {
                clearBox: function(){
                    elem.attr('value','');
                    elemBox.find('span').remove();
                    place.show();
                }
            };

            $('.layuiTableSelect').on('click', function () {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            //分页选中保存数组
            table.on('radio('+tableName+')', function(obj){
                if(opt.checkedKey){
                    checkedData = table.checkStatus(tableName).data
                }
                var ra = table.checkStatus(tableName).data[0];
                elem.attr('value',ra[opt.checkedKey]);
                place.hide();
                elemBox.find('span').remove();
                elemBox.append('<span class="t-select" data-value="'+ ra[opt.checkedKey] +'" id="tSelect_'+ ra[opt.checkedKey] +'">'+ ra[opt.checkedName] +'<i class="layui-icon layui-icon-close lay-table-select-close" onclick="event.stopPropagation();var id = $(this).parent().attr(\'data-value\'),idz = $(this).parent().parent().prev().attr(\'value\').split(\',\');for(var g=0;g < idz.length;g++){if(id == idz[g]){idz.splice(g,1);$(this).parent().parent().prev().attr(\'value\',idz);$(this).parent().remove();if($(\'.t-select-box\').find(\'span\').length == 0){$(\'.t-select-box\').find(\'p\').show()}}}"></i></span>')
                updataButton(table.checkStatus(tableName).data.length)
            })
			table.on('checkbox('+tableName+')', function(obj){
                if(opt.checkedKey){
                    if(obj.checked){
                        ids = [];
                        for (var i=0;i<elemBox.find('span').length;i++){
                            ids.push(elemBox.find('span').eq(i).attr('data-value'))
                        }
                        $.each(table.checkStatus(tableName).data,function(index,item){
                            var index = $.inArray(item[opt.checkedKey].toString(),ids);
                            if(index == '-1'){
                                place.hide();
                                elemBox.append('<span class="t-select" data-value="'+ item[opt.checkedKey] +'" id="tSelect_'+ item[opt.checkedKey] +'">'+ item[opt.checkedName] +'<i class="layui-icon layui-icon-close lay-table-select-close" onclick="event.stopPropagation();var id = $(this).parent().attr(\'data-value\'),idz = $(this).parent().parent().prev().attr(\'value\').split(\',\');for(var g=0;g < idz.length;g++){if(id == idz[g]){idz.splice(g,1);$(this).parent().parent().prev().attr(\'value\',idz);$(this).parent().remove();if($(\'.t-select-box\').find(\'span\').length == 0){$(\'.t-select-box\').find(\'p\').show()}}}"></i></span>')
                            }
                            //
                        });
                        for (var i=0;i<table.checkStatus(tableName).data.length;i++){
                            checkedData.push(table.checkStatus(tableName).data[i])
                        }
                        checkedData = uniqueObjArray(checkedData, opt.checkedKey);
                        var zzz = [];
                        $.each(checkedData,function(index,item){
                            zzz.push(item[opt.checkedKey]);
                        })
                        if(elem.attr('value') != ''){
                            zzz = elem.attr('value').split(',').concat(zzz);
                        }
                        zzz = uniq(zzz);
                        elem.attr('value',zzz);
                    }else{
                        if(obj.type=='all'){
                            for (var j=0;j<table.cache[tableName].length;j++) {
                                for (var i=0;i<checkedData.length;i++){
                                    if(checkedData[i][opt.checkedKey] == table.cache[tableName][j][opt.checkedKey]){
                                        checkedData.splice(i,1)
                                    }
                                }
                            }
                        }else{
                            //因为LAYUI问题，操作到变化全选状态时获取到的obj为空，这里用函数获取未选中的项。
                            function nu (){
                                var noCheckedKey = '';
                                for (var i=0;i<table.cache[tableName].length;i++){
                                    if(!table.cache[tableName][i].LAY_CHECKED){
                                        noCheckedKey = table.cache[tableName][i][opt.checkedKey];
                                    }
                                }
                                return noCheckedKey
                            }
                            var noCheckedKey = obj.data[opt.checkedKey] || nu();
                            for (var i=0;i<checkedData.length;i++){
                                if(checkedData[i][opt.checkedKey] == noCheckedKey){
                                    checkedData.splice(i,1);
                                }
                            }
                        }
                        checkedData = uniqueObjArray(checkedData, opt.checkedKey);
                        var zzz = [],
                            kkk = [];
                        $.each(checkedData,function(index,item){
                            zzz.push(item[opt.checkedKey]);
                        })
                        for (var i=0;i<elemBox.find('span').length;i++){
                            var index = $.inArray(parseInt(elemBox.find('span').eq(i).attr('data-value')),zzz);
                            if(index == '-1'){
                                kkk.push(elemBox.find('span').eq(i).attr('data-value'))
                            }
                        }
                        $.each(kkk,function(index,item){
                            $('#tSelect_'+item).remove();
                        })
                        // $('#tSelect_'+elemBox.find('span').eq(i).attr('data-value')).remove();
                        elem.attr('value',zzz);
                    }
                    if(elemBox.find('span').length == '0'){
                        place.show();
                    }
                    updataButton(checkedData.length)
                }else{
                    updataButton(table.checkStatus(tableName).data.length)
                }
            });

            
            //写入默认选中值(puash checkedData)
            function defaultChecked (res, curr, count){
                // if(opt.checkedKey && elem.attr('value')){
                    var selected = elem.attr('value').split(",");
                    for(var i=0;i<res.data.length;i++){
                        for(var j=0;j<selected.length;j++){
                            if(res.data[i][opt.checkedKey] == selected[j]){
                                checkedData.push(res.data[i])
                            }
                        }
                    }
                    checkedData = uniqueObjArray(checkedData, opt.checkedKey);
                    var d = [];
                    for (var i=0;i<checkedData.length;i++){
                        var index = $.inArray(checkedData[i][opt.checkedKey].toString(),selected);
                        if(index > -1){
                            d.push(checkedData[i]);
                        }
                    }
                    checkedData = d;
                    // console.log(checkedData)
                // }
            }

            //渲染表格后选中
            function setChecked (res, curr, count) {
                // console.log(checkedData)
                for(var i=0;i<res.data.length;i++){
                    for (var j=0;j<checkedData.length;j++) {
                        if(res.data[i][opt.checkedKey] == checkedData[j][opt.checkedKey]){
                            res.data[i].LAY_CHECKED = true;
                            var index= res.data[i]['LAY_TABLE_INDEX'];
                            var checkbox = $('#'+tableName+'').next().find('tr[data-index=' + index + '] input[type="checkbox"]');
                            checkbox.prop('checked', true).next().addClass('layui-form-checked');
                            var radio  = $('#'+tableName+'').next().find('tr[data-index=' + index + '] input[type="radio"]');
                            radio.prop('checked', true).next().addClass('layui-form-radioed').find("i").html('&#xe643;');
                        }
                    }
                }
                var checkStatus = table.checkStatus(tableName);
                if(checkStatus.isAll){
                    $('#'+tableName+'').next().find('.layui-table-header th[data-field="0"] input[type="checkbox"]').prop('checked', true);
                    $('#'+tableName+'').next().find('.layui-table-header th[data-field="0"] input[type="checkbox"]').next().addClass('layui-form-checked');
                }
                updataButton(checkedData.length)
            }

			//更新选中数量
			function updataButton (n) {
				// tableBox.find('.tableSelect_btn_select span').html(n==0?0:'('+n+')')
                if(elemBox.find('span').length == '0'){
                    place.show();
                }else{
                    place.hide();
                }
				tableBox.find('.table-selected span').html(elemBox.find('span').length)
            }


			//FIX位置
			var overHeight = (elem.offset().top + elem.outerHeight() + tableBox.outerHeight() - $(window).scrollTop()) > $(window).height();
			var overWidth = (elem.offset().left + tableBox.outerWidth()) > $(window).width();
			    overHeight && tableBox.css({'top':'65px','bottom':'auto'});
			    overWidth && tableBox.css({'left':'auto','right':'5px'})
			
            //关键词搜索
            form.on('submit(tableSelect_btn_search)', function(data){
                tableSelect_table.reload({
                    where: data.field,
                    page: {
                      curr: 1
                    }
                  });
                return false;
            });

            //双击行选中
            table.on('rowDouble('+tableName+')', function(obj){
                var checkStatus = {data:[obj.data]};
                selectDone(checkStatus);
            })

            //按钮选中
            tableBox.find('.tableSelect_btn_select').on('click', function() {
                var checkStatus = table.checkStatus(tableName);
                if(checkedData.length > 1){
                	checkStatus.data = checkedData;
                }
                selectDone(checkStatus);
            })

            //写值回调和关闭
            function selectDone (checkStatus){
                if(opt.checkedKey){
                    var selected = [];
                    for(var i=0;i<checkStatus.data.length;i++){
                        selected.push(checkStatus.data[i][opt.checkedKey])
                    }
                    elem.val(selected.join(","));
                }
                opt.done(elem, checkStatus);
                tableBox.hide();
                delete table.cache[tableName];
                checkedData = [];
            }
            
            //点击其他区域关闭
            $(document).mouseup(function(e){
                var userSet_con = $(''+opt.elem+',.tableSelect');
                if(!userSet_con.is(e.target) && userSet_con.has(e.target).length === 0){
                    tableBox.hide();
                    $('.layui-table-tips').remove();
                    delete table.cache[tableName];
                    // checkedData = [];
                }
            });
        })
    }
    //数组去重
    function uniqueObjArray(arr, type){
        var newArr = [];
        var tArr = [];
        if(arr.length == 0){
            return arr;
        }else{
            if(type){
                for(var i=0;i<arr.length;i++){
                    if(!tArr[arr[i][type]]){
                        newArr.push(arr[i]);
                        tArr[arr[i][type]] = true;
                    }
                }
                return newArr;
            }else{
                for(var i=0;i<arr.length;i++){
                    if(!tArr[arr[i]]){
                        newArr.push(arr[i]);
                        tArr[arr[i]] = true;
                    }
                }
                return newArr;
            }
        }
    }
    function uniq(array){
        var temp = []; //一个新的临时数组
        for(var i = 0; i < array.length; i++){
            if(temp.indexOf(parseInt(array[i])) == -1){
                temp.push(parseInt(array[i]));
            }
        }
        return temp;
    }

    /**
    * 隐藏选择器
    */
    tableSelect.prototype.hide = function (opt) {
        $('.tableSelect').remove();
    }

    //自动完成渲染
    var tableSelect = new tableSelect();

    //FIX 滚动时错位
    if(window.top == window.self){
        $(window).scroll(function () {
            tableSelect.hide();
        });
    }

    exports(MOD_NAME, tableSelect);
})