layui.use(['form', 'admin'], function () {
    var $ = layui.jquery;
    var form = layui.form;
    var admin = layui.admin;
    var setter = admin.setter;
    var $body = $('body');

    // 切换主题
    var $themItem = $('.more-theme-item');
    $themItem.click(function () {
        $themItem.removeClass('active');
        $(this).addClass('active');
        admin.changeTheme($(this).data('theme'));
    });
    var theme = $body.data('theme');
    if (theme) {
        $themItem.removeClass('active');
        $themItem.filter('[data-theme="' + theme + '"]').addClass('active');
    }

    // 关闭/开启页脚
    form.on('switch(setFooter)', function (data) {
        var checked = data.elem.checked;
        admin.putSetting('closeFooter', !checked);
        checked ? $body.removeClass('close-footer') : $body.addClass('close-footer');
    });
    $('#setFooter').prop('checked', !$body.hasClass('close-footer'));

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
    var $mainTab = $('.layui-body>.layui-tab[lay-filter="admin-pagetabs"]');
    form.on('switch(setRefresh)', function (data) {
        var checked = data.elem.checked;
        admin.putSetting('tabAutoRefresh', checked);
        checked ? $mainTab.attr('lay-autoRefresh', 'true') : $mainTab.removeAttr('lay-autoRefresh');
    });
    $('#setRefresh').prop('checked', setter.tabAutoRefresh === true);

    // 导航小三角
    var $leftNav = $('.layui-layout-admin>.layui-side>.layui-side-scroll>.layui-nav');
    form.on('radio(navArrow)', function (data) {
        $leftNav.removeClass('arrow2 arrow3');
        data.value && $leftNav.addClass(data.value);
        admin.putSetting('navArrow', data.value);
    });
    var navArrow = $leftNav.hasClass('arrow2') ? 'arrow2' : $leftNav.hasClass('arrow3') ? 'arrow3' : '';
    $('[name="navArrow"][value="' + navArrow + '"]').prop('checked', true);

    form.render('radio', 'more-set-form');
    form.render('checkbox', 'more-set-form');
});