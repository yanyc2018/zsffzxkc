<?php
namespace think\gateway;


use GatewayWorker\Lib\Gateway;

class Events
{
    // -----------------  常量定义  --------------
    /**
     * 客户端响应的心跳数据，定义为静态属性方便外部调用
     * @var string
     */
    public static $CLIENT_PING_DATA = '2';

    /**
     * 客户端连接后服务端给客户端发送初始化事件数据的操作key值
     * 详见 $initEventValue 字段注释
     * @var string
     */
    public static $INIT_EVENT_KEY = 'type';

    /**
     * 客户端连接后服务端首次给客户端发送的json数据的操作对应的值
     * 当客户端连接服务端后, 服务端会直接给客户端发送一个初始化事件, 将client_id返回
     * 如 $initEventKey 设置为 type, $initEventValue 设置为 init,
     * 则初始化后服务端给客户端发送一次格式为: {type: 'init', client_id: xxxxxx } 的消息
     * 客户端可以通过此事件获取client_id并到业务系统中将client_id注册到当前用户中
     * @var string
     */
    public static $INIT_EVENT_VALUE = 'init';


    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        // 向当前client_id发送数据
        Gateway::sendToClient($client_id, json_encode([
            self::$INIT_EVENT_KEY => self::$INIT_EVENT_VALUE,
            'client_id' => $client_id,
        ]));
    }

    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage($client_id, $message)
    {
        // 如果客户端发送的是心跳消息, 什么都不干
        if($message == self::$CLIENT_PING_DATA){
            return ;
        }
        self::processMessage($client_id, $message);
    }

    /**
     * 处理客户端发来的消息
     * @param $client_id
     * @param $message
     */
    public static function processMessage($client_id, $message)
    {
        // 向除了自己的所有人发送
        Gateway::sendToAll("$client_id said $message\r\n", null, $client_id);
    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id)
    {
        // 向所有人发送
        GateWay::sendToAll("$client_id logout\r\n");
    }
}
