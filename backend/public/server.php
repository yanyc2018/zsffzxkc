<?php

namespace think;


// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('BIND_MODULE','livechat/Start');
// 加载基础文件
//require __DIR__ . '/../thinkphp/base.php';
require __DIR__ . '/../thinkphp/start.php';
// 执行应用并响应，此处一定要绑定默认模块，否则默认执行tp配置文件中默认访问模块
//Container::get('app')->bind('livechat/Start')->path(APP_PATH)->run()->send();