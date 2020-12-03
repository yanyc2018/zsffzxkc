<?php
/**
 * 知识付费在线课程v2.0.0
 * Author: 山西匠言网络科技有限公司
 * This is not a free software, it under the license terms, you can visit https://www.zsffzxkc.cn/ get more details.
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Index extends  Base
{
    /**
     * 框架页面
     * @return mixed
     */
    public function index()
    {
        $title = Db::name ('config')->where ('id' , 1)->find ();
        $copyright = Db::name ('config')->where ('id' , 2)->find ();
        $version = Db::name ('config')->where ('id' , 50)->find ();
        $this->assign ('title',$title);
        $this->assign ('copyright',$copyright);
        $this->assign ('version',$version);
        $this->assign ('admin_name',session('username'));
        return $this->fetch('/index');
    }

    /**
     * 首页
     * @return mixed|\think\response\Json
     */
    public function indexPage()
    {
        $acid=1;
        $version = Db::name ('config')->where ('id' , 50)->find ();
        $this->assign ('version',$version);
        //当天开始时间
        $start_time=strtotime(date("Y-m-d",time()));
        //当天结束时间
        $end_time=$start_time+60*60*24;
        $today_usernum=db('user')->where(['acid'=>$acid])->where('createtime','egt',$start_time)->where('createtime','lt',$end_time)->count();
        $user_total=db('user')->where(['acid'=>$acid])->count();
        $orders=db('mediaorder')->where(['acid'=>$acid,'is_pay'=>1])->where('paytime','egt',$start_time)->where('paytime','lt',$end_time)->select();
        $today_ordernum=db('mediaorder')->where(['acid'=>$acid,'is_pay'=>1])->where('paytime','egt',$start_time)->where('paytime','lt',$end_time)->count();
        $totay_money=0;
        foreach ($orders as $k=>$v){
            $totay_money+=$v['price'];
        }
        //近7天
        $seven_daystime=time()-7*60*60*24;
        $orders=db('mediaorder')->where(['acid'=>$acid,'is_pay'=>1])->where('paytime','egt',$seven_daystime)->where('paytime','elt',time())->select();
        $seven_ordernum=db('mediaorder')->where(['acid'=>$acid,'is_pay'=>1])->where('paytime','egt',$seven_daystime)->where('paytime','elt',time())->count();
        $sevenday_money=0;
        foreach ($orders as $k=>$v){
            $sevenday_money+=$v['price'];
        }
        //本月
        //获取本月开始的时间戳
        $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
        //获取本月结束的时间戳
        $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $orders=db('mediaorder')->where(['acid'=>$acid,'is_pay'=>1])->where('paytime','egt',$beginThismonth)->where('paytime','elt',$endThismonth)->select();
        $Thismonth_money=0;
        foreach ($orders as $k=>$v){
            $Thismonth_money+=$v['price'];
        }
        //上月
        $m = date('Y-m-d', mktime(0,0,0,date('m')-1,1,date('Y'))); //上个月的开始日期
        $t = date('t',strtotime($m)); //上个月共多少天
        $start = date('Y-m-d', mktime(0,0,0,date('m')-1,1,date('Y'))); //上个月的开始日期
        $start=strtotime($start);
        $end = date('Y-m-d', mktime(0,0,0,date('m')-1,$t,date('Y'))); //上个月的结束日期
        $end =strtotime($end);
        $orders=db('mediaorder')->where(['acid'=>$acid,'is_pay'=>1])->where('paytime','egt',$start)->where('paytime','lt',$end)->select();
        $Lastmonth_money=0;
        foreach ($orders as $k=>$v){
            $Lastmonth_money+=$v['price'];
        }
        //最近20条日志
        $logs=db('log')->order('add_time DESC')->limit(30)->select();
        foreach ($logs as $k=>$v){
            $logs[$k]['date']=date('Y-m-d H:i:s',$v['add_time']);
        }
        //最新订单
        $neworders=db('mediaorder')->where(['acid'=>$acid,'is_pay'=>1])->order('paytime desc')->limit(5)->select();
        $item=array(
            'today_usernum'=>$today_usernum,
            'user_total'=>$user_total,
            'today_ordernum'=>$today_ordernum,
            'totay_money'=>$totay_money,
            'seven_ordernum'=>$seven_ordernum,
            'sevenday_money'=>$sevenday_money,
            'Thismonth_money'=>$Thismonth_money,
            'Lastmonth_money'=>$Lastmonth_money,
            'logs'=>$logs,
            'neworders'=>$neworders
        );
        $this->assign('item',$item);
        return $this->fetch('index/index');
    }

    /**
     * 清除缓存
     */
    public function clear() {
        if (delete_dir_file(CACHE_PATH) && delete_dir_file(TEMP_PATH)) {
            writelog('清除缓存成功',200);
            return json(['code' => 200, 'msg' => '清除缓存成功']);
        } else {
            writelog('清除缓存失败',100);
            return json(['code' => 100, 'msg' => '清除缓存失败']);
        }
    }
    //主题
    public function theme()
    {
        $a="<div class='layui-card-header'>主题设置</div>
<div class='more-theme-list'>
    <div class='more-theme-item active'>
        <img src='../static/assets/module/img/theme-admin.png'/>
    </div>
    <div class='more-theme-item' data-theme='theme-cyan'>
        <img src='../static/assets/module/img/theme-cyan.png'/>
    </div>
    <div class='more-theme-item' data-theme='theme-white'>
        <img src='../static/assets/module/img/theme-white.png'/>
    </div>
    <div class='more-theme-item' data-theme='theme-pink'>
        <img src='../static/assets/module/img/theme-pink.png'/>
    </div>
    <div class='more-theme-item' data-theme='theme-colorful'>
        <img src='../static/assets/module/img/theme-colorful.png'/>
    </div>
    <div class='more-theme-item' data-theme='theme-blue'>
        <img src='../static/assets/module/img/theme-blue.png'/>
    </div>
    <div class='more-theme-item' data-theme='theme-green'>
        <img src='../static/assets/module/img/theme-green.png'/>
    </div>
    <div class='more-theme-item' data-theme='theme-purple'>
        <img src='../static/assets/module/img/theme-purple.png'/>
    </div>
    <div class='more-theme-item' data-theme='theme-red'>
        <img src='../static/assets/module/img/theme-red.png'/>
    </div>
</div>
<!-- 导航 -->
<div class='more-menu-list'>
    <a class='more-menu-item' href='https://www.zsffzxkc.cn/' target='_blank'>
        <i class='layui-icon layui-icon-read' style='font-size: 19px;'></i> 开发文档
    </a>
</div>
<!-- 控制开关 -->
<div class='layui-form' style='margin: 25px 0;' lay-filter='more-set-form'>
    <div class='layui-form-item'>
        <label class='set-item-label'>页&emsp;脚：</label>
        <div class='set-item-ctrl'>
            <input id='setFooter' lay-filter='setFooter' type='checkbox' lay-skin='switch' lay-text='开启|关闭'>
        </div>
        <label class='set-item-label'> Tab&nbsp;记忆：</label>
        <div class='set-item-ctrl'>
            <input id='setTab' lay-filter='setTab' type='checkbox' lay-skin='switch' lay-text='开启|关闭'>
        </div>
    </div>
    <div class='layui-form-item'>
        <label class='set-item-label'>多标签：</label>
        <div class='set-item-ctrl'>
            <input id='setMoreTab' lay-filter='setMoreTab' type='checkbox' lay-skin='switch' lay-text='开启|关闭'>
        </div>
        <label class='set-item-label'>切换刷新：</label>
        <div class='set-item-ctrl'>
            <input id='setRefresh' lay-filter='setRefresh' type='checkbox' lay-skin='switch' lay-text='开启|关闭'>
        </div>
    </div>
    <div class='layui-form-item'>
        <label class='set-item-label'>导航箭头：</label>
        <div class='set-item-ctrl'>
            <input lay-filter='navArrow' type='radio' value='' title='默认' name='navArrow'>
            <input lay-filter='navArrow' type='radio' value='arrow2' title='箭头' name='navArrow'>
            <input lay-filter='navArrow' type='radio' value='arrow3' title='加号' name='navArrow'>
        </div>
    </div>
</div>";

        $b="
<script>
    layui.use(['form', 'admin'], function () {
        var $ = layui.jquery;
        var form = layui.form;
        var admin = layui.admin;
        var setter = admin.setter;
        var body = $('body');

        // 切换主题
        var themItem = $('.more-theme-item');
        themItem.click(function () {
            themItem.removeClass('active');
            $(this).addClass('active');
            admin.changeTheme($(this).data('theme'));
        });
        var theme = body.data('theme');
        if (theme) {
            themItem.removeClass('active');
            themItem.filter('[data-theme=\"' + theme + '\"]').addClass('active');
        }

        // 关闭/开启页脚
        form.on('switch(setFooter)', function (data) {
            var checked = data.elem.checked;
            admin.putSetting('closeFooter', !checked);
            checked ? body.removeClass('close-footer') : body.addClass('close-footer');
        });
        $('#setFooter').prop('checked', !body.hasClass('close-footer'));

        // 关闭/开启Tab记忆功能
        form.on('switch(setTab)', function (data) {
            layui.index.setTabCache(data.elem.checked);
        });
        $('#setTab').prop('checked', setter.cacheTab);

        // 关闭/开启多标签
        form.on('switch(setMoreTab)', function (data) {
            var checked = data.elem.checked;
            admin.putSetting('pageTabs', checked);
            admin.putTempData('indexTabs', undefined);
            location.reload();
        });
        $('#setMoreTab').prop('checked', setter.pageTabs);

        // 切换Tab自动刷新
        var mainTab = $('.layui-body>.layui-tab[lay-filter=\"admin-pagetabs\"]');
        form.on('switch(setRefresh)', function (data) {
            var checked = data.elem.checked;
            admin.putSetting('tabAutoRefresh', checked);
            checked ? mainTab.attr('lay-autoRefresh', 'true') : mainTab.removeAttr('lay-autoRefresh');
        });
        $('#setRefresh').prop('checked', setter.tabAutoRefresh === true);

        // 导航小三角
        var leftNav = $('.layui-layout-admin>.layui-side>.layui-side-scroll>.layui-nav');
        form.on('radio(navArrow)', function (data) {
            leftNav.removeClass('arrow2 arrow3');
            data.value && leftNav.addClass(data.value);
            admin.putSetting('navArrow', data.value);
        });
        var navArrow = leftNav.hasClass('arrow2') ? 'arrow2' : leftNav.hasClass('arrow3') ? 'arrow3' : '';
        $('[name=\"navArrow\"][value=\"' + navArrow + '\"]').prop('checked', true);

        form.render('radio', 'more-set-form');
        form.render('checkbox', 'more-set-form');
    });
</script>";
        $c="<style>
    /* theme */
    .more-theme-list {
        padding-left: 15px;
        padding-top: 20px;
        margin-bottom: 10px;
    }

    .more-theme-item {
        padding: 4px;
        margin: 0 6px 15px 0;
        display: inline-block;
        border: 1px solid transparent;
    }

    .more-theme-item img {
        width: 80px;
        height: 50px;
        background: #f5f7f9;
        box-sizing: border-box;
        border: 1px solid #f5f7f9;
        cursor: pointer;
    }

    .more-theme-item:hover, .more-theme-item.active {
        border-color: #5FB878;
    }

    .more-menu-item {
        color: #595959;
        height: 50px;
        line-height: 50px;
        font-size: 16px;
        padding: 0 25px;
        border-bottom: 1px solid #e8e8e8;
        font-style: normal;
        display: block;
    }

    /* menu */
    .more-menu-item:first-child {
        border-top: 1px solid #e8e8e8;
    }

    .more-menu-item:hover {
        color: #595959;
        background: #f6f6f6;
    }

    .more-menu-item .layui-icon {
        font-size: 18px;
        padding-right: 10px;
    }

    .more-menu-item:after {
        color: #8c8c8c;
        right: 16px;
        content: '\\e602';
        position: absolute;
        font-family: layui-icon !important;
    }

    .more-menu-item.no-icon:after {
        display: none;
    }

    /* setting from */
    .set-item-label {
        height: 38px;
        line-height: 38px;
        padding-left: 20px;
        display: inline-block;
    }

    .set-item-ctrl {
        height: 38px;
        line-height: 38px;
        display: inline-block;
    }

    .set-item-ctrl > * {
        margin: 0 !important;
    }
</style>";
        echo $a;
        echo $b;
        echo $c;
    }
    public function webuploader(){
        return $this->fetch('/webuploader');
    }
}