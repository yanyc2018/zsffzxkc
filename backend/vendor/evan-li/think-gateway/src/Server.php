<?php
namespace think\gateway;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Workerman\Worker;

/**
 * Class Server
 * BusinessWorker控制器扩展
 */
class Server
{

    /**
     * 构造方法, 初始化BusinessWorker  并根据需求初始化
     * Server constructor.
     */
    public function __construct()
    {
        $config = $gatewayWorkerConfig = require GATEWAY_WORKER_CONFIG_PATH . '/config.php';
        $registerConfig = $config['register'];
        $businessConfig = $config['business'];

        // 初始化Business线程
        $business = new BusinessWorker();
        // 给注册服务属性赋值 (只赋值支持的属性)
        foreach (['name', 'count', 'eventHandler', 'processTimeout', 'processTimeoutHandler', 'secretKey'] as $key) {
            isset($businessConfig[$key]) && $business->$key = $businessConfig[$key];
        }
        // 服务注册地址
        $business->registerAddress = $registerConfig['address'];

        // 如果不是在根目录启动，则运行runAll方法
        if(!defined('GLOBAL_START')) {
            Worker::runAll();
        }
    }

    /**
     * 空操作, 批量启动时, 在构造方法中没有挂起线程, 需要一个空操作让框架可以正常返回
     */
    public function _empty() {}
}
