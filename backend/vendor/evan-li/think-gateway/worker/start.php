#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Workerman\Worker;
use think\gateway\Utils;

Utils::checkExtension();

// 标记是全局启动
define('GLOBAL_START', 1);

// 加载所有/start/start_*.php，以便启动所有服务
foreach(glob(__DIR__ . '/starter/start_*.php') as $start_file)
{
    require_once $start_file;
}
// 运行所有服务
Worker::runAll();
