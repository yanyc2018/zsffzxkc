<?php
/**
 * 配置文件, 配置各个worker的连接及相关信息
 */
return [

    // 注册服务配置
    'register' => [
        // 注册服务地址，用于gateway及business服务注册 (必须)
        'address'   => '127.0.0.1:1238',
        // 网关服务线程名称，status方便查看
        'name'      => 'RegisterWorker',
        // register服务秘钥
        'secretKey' => '',
    ],

    // gateway服务配置
    'gateway'  => [
        // gateway监听地址，用于客户端连接 (必须)
        'socket'               => 'websocket://0.0.0.0:2345',
        // 网关服务线程名称，status方便查看
        'name'                 => 'GatewayWorker',
        // gateway进程数
        'count'                => 4,
        // 本机ip，分布式部署时使用内网ip，用于与business内部通讯
        'lanIp'                => '127.0.0.1',
        // 内部通讯起始端口，每个 gateway 实例应该都不同，假如$gateway->count=4，起始端口为4000        则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口
        'startPort'            => 2900,
        // 是否可以平滑重启，gateway 不能平滑重启，否则会导致连接断开
//        'reloadable'           => false,
        // 心跳时间间隔，设为0则表示不检测心跳
        'pingInterval'         => 25,
        // $gatewayPingNotResponseLimit * $gatewayPingInterval 时间内，客户端未发送任何数据，断开客户端连接 (设为0表示不监听客户端返回数据)
        'pingNotResponseLimit' => 2,
        // 服务端向客户端发送的心跳数据，为空不给客户端发送心跳数据
        'pingData'             => 2,
        // gateway服务秘钥
        'secretKey'            => '',
    ],

    // business服务配置
    'business' => [
        // business服务名称，status方便查看
        'name'                  => 'BusinessWorker',
        // business进程数
        'count'                 => 4,
        // 业务服务事件处理
        'eventHandler'          => 'think\gateway\Events',
        // 业务超时时间，可用来定位程序卡在哪里
        'processTimeout'        => 30,
        // 业务超时后的回调，可用来记录日志
        'processTimeoutHandler' => '\\Workerman\\Worker::log',
        // 业务服务秘钥
        'secretKey'             => '',
        // 是否允许多进程监听同一端口, php7才支持
//        'reusePort'             => false,
    ],
];