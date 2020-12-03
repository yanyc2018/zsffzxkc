<?php

namespace think\gateway;

/**
 * Class Utils
 * think-gateway工具类
 * @package think\gateway
 */
class Utils
{
    /**
     * 检查需要的扩展是否加载
     */
    public static function checkExtension()
    {
        if(strpos(strtolower(PHP_OS), 'win') !== 0) {
            // 检查扩展
            if(!extension_loaded('pcntl')) {
                exit ("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
            }

            if(!extension_loaded('posix')) {
                exit ("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
            }
        }
    }
}