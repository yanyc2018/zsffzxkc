#!/usr/bin/env php
<?php

// 加载启动项
require_once __DIR__ . '/../../vendor/autoload.php';

use \think\gateway\Utils;

// 检查扩展是否加载
Utils::checkExtension();

define('APP_PATH', __DIR__ . '/../../application/');

define('BIND_MODULE','worker/Starter');

// 定义服务启动项
define('START_BUSINESS', true);

// 加载gatewayWorker配置文件
define('GATEWAY_WORKER_CONFIG_PATH', __DIR__);

// 加载框架引导文件
require __DIR__ . '/../../thinkphp/start.php';