#!/usr/bin/env php
<?php

// 加载启动项
require_once __DIR__ . '/../../vendor/autoload.php';

use \Workerman\Worker;
use \GatewayWorker\Gateway;
use \think\gateway\Utils;

// 检查扩展是否加载
Utils::checkExtension();

$config = require __DIR__ . '/config.php';
$registerConfig = $config['register'];
$gatewayConfig = $config['gateway'];

// gateway 进程，使用配置的值
$gateway = new Gateway($gatewayConfig['socket']);
// 给注册服务属性赋值 (只赋值支持的属性)
foreach (['name', 'count', 'lanIp', 'startPort', 'pingInterval', 'pingNotResponseLimit', 'pingData', 'secretKey'] as $key) {
    isset($gatewayConfig[$key]) && $gateway->$key = $gatewayConfig[$key];
}
// 服务注册地址
$gateway->registerAddress = $registerConfig['address'];

/*
// 当客户端连接上来时，设置连接的onWebSocketConnect，即在websocket握手时的回调
$gateway->onConnect = function($connection)
{
    $connection->onWebSocketConnect = function($connection , $http_header)
    {
        // 可以在这里判断连接来源是否合法，不合法就关掉连接
        // $_SERVER['HTTP_ORIGIN']标识来自哪个站点的页面发起的websocket链接
        if($_SERVER['HTTP_ORIGIN'] != 'http://kedou.workerman.net')
        {
            $connection->close();
        }
        // onWebSocketConnect 里面$_GET $_SERVER是可用的
        // var_dump($_GET, $_SERVER);
    };
};*/


// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START')) {
    Worker::runAll();
}