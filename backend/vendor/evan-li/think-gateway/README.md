[TOC]

# think-gateway扩展

基于tp5的gateway worker扩展

## 结构说明:

~~~
vendor (composer第三方库目录)
├─src                         核心代码目录
│  ├─Server.php               GatewayWorker扩展控制器文件
│  └─Events.php               默认的消息事件处理类
│
├─worker                      worker应用入口目录
│  ├─start                    服务启动目录
│  │  ├─config.php            注册服务启动文件
│  │  ├─start_register.php    注册服务启动文件
│  │  ├─start_gateway.php     gateway(网关)服务启动文件
│  │  └─start_business.php    业务服务启动文件
│  │  
│  ├─start.php                linux系统服务启动文件
│  └─start-for-win.bat        windows系统服务启动批处理文件
│
├─composer.json               composer 定义文件
├─LICENSE                     授权说明文件
└─README.md                   README 文件
~~~



## 使用介绍

### 安装:

1. 创建thinkphp5项目

   ```sh
   composer create-project topthink/think gateway
   ```


2. 添加think-gateway依赖

   ```sh
   composer require evan-li/think-gateway
   ```

   >  windows版本请使用`evan-li/think-gateway-for-win`包安装 : 
   >
   >  ```
   >  composer require evan-li/think-gateway-for-win
   >  ```

> 如果没有使用过composer, 请先看 [composer入门](http://docs.phpcomposer.com/00-intro.html) , 可以使用[composer中国镜像](https://pkg.phpcomposer.com/)
>



### 启动服务: 

1.  创建一个`Starter`控制器，继承`think\gateway\Server`类,用来启动Worker

    `application/worker/controller/Starter.php`

    ```php
    <?php
    namespace app\worker\controller;

    use think\gateway\Server;

    class Starter extends Server
    {

    }
    ```

2.  创建worker应用入口

        将的`worker` 目录拷贝到项目根目录中, `worker` 目录中包含了服务启动所需要的文件

3.  运行服务

    1. 在linux中, 首先切换到`worker`目录中,  执行命令: 

       ```sh
       php ./start.php start
       ```

    2. windows系统中, 直接执行worker目录中的 `start-for-win.bat` 批处理文件


*到此为止, 我们的gateway-worker服务就跑起来啦*  :smile:



### 消息处理

处理客户端发送的消息时, 增加一个`MessageHandler`类, 继承`think\gateway\Events`类, 并实现`processMessage`方法即可, 需要注意的是, `processMessage`方法是静态方法

增加`MessageHandler`类之后需要在`Starter`控制器中设置消息处理Handler:

`app\worker\controller\Starter 类:`

```php
class Starter extends Server
{

    protected $businessEventHandler = 'app\worker\util\EventsHandler';

}
```

> 默认不设置消息处理类的时候, 调用的是Events类

`app\worker\util\EventsHanler 类:`

```php
<?php
namespace app\worker\util;

use think\gateway\Events;

class EventsHandler extends Events
{

    public static function processMessage($client_id, $message)
    {
        parent::processMessage($client_id, $message); // do some thing
    }
}
```





### 配置说明

配置文件位于`worker/start/config.php`文件中, 包含了各个服务的配置信息, 具体说明请查看文件: [config.php](./worker/starter/config.php)

> Windows中, 由于线程操作支持的问题, 所有的count *(子worker启动的线程数)* 配置都不会生效



### 分布式部署

如果需要分布式部署,我们可以通过`worker/start`目录中的对应的启动文件来控制启动哪个服务来控制启动哪个服务
```php    
// 启动文件说明
start_register.php    注册服务启动文件
start_gateway.php     gateway(网关)服务启动文件
start_business.php    业务服务启动文件
```
 分布式部署时根据需要的服务启动对应的服务

> 处于必要性考虑, register与gateway服务启动时不再加载tp5框架, 只有business服务会加载框架, 以方便在业务服务中使用框架的各种功能



##  Demo项目目录结构 *(只做参考用)*:

> 使用正常的tp5目录结构, 框架本身相关的文件及目录不再展示

```
www  WEB部署目录（或者子目录）
├─application                  应用目录
│  ├─worker                    worker模块目录
│  │  ├─controller             控制器目录
│  │  │  └─Starter.php         gateway启动控制器文件
│  │  └─util                   工具类目录
│  │     └─EventsHandler.php   business线程事件处理工具类
│  └─ ...                      其他框架相关文件及目录
│
├─public                       WEB目录（对外访问目录）
│  ├─start-for-win             windows版本启动文件目录
│  │  ├─register.php           注册服务启动文件
│  │  ├─gateway.php            gateway(网关)服务启动文件
│  │  └─business.php           业务服务启动文件
│  ├─start.php                 linux系统启动文件
│  ├─start-for-win.bat         windows系统服务启动批处理文件
│  └─...                       其他框架相关文件及目录
│
├─vendor                       第三方类库目录（Composer依赖库）
│  ├─evan-li                   
│  │  └─think-gateway          think-gateway类库目录
│  └─...                       其他第三方类库目录
└─...                          其他框架相关文件及目录
```



>  [demo项目地址](https://github.com/evan-li/think-gateway-demo)




##  类说明 

### Server类介绍

Server类是基于GatewayWorker的控制器扩展类, 使用自己的控制器继承Server类即可, 不需要实现任何方法, 只是起一个框架入口控制器的作用

> 服务相关的配置信息已归纳到配置文件中, 见: [config.php](./worker/starter/config.php) 文件中





### Events类介绍

`think\gateway\Events`类简单封装了一个连接的初始化事件响应,以及心跳信息忽略, 建议自定义的Events类直接继承 `think\gateway\Events`类并实现具体的 `processMessage`方法即可

#### $INIT_EVENT_KEY 属性

```
客户端连接后服务端给客户端发送初始化事件数据的操作key值
```

#### $INIT_EVENT_VALUE 属性

```
客户端连接后服务端首次给客户端发送初始化事件数据的操作名
```

当客户端连接服务端后, 服务端会直接给客户端发送一个初始化事件, 将client_id返回
如`$initEventKey`设置为 `action`, `$initEventValue` 设置为 `init`,
则初始化后服务端给客户端发送一次格式为:` {action: 'init', client_id: xxxxxx } `的消息
客户端可以通过此事件获取client_id并到业务系统中将client_id注册到当前用户中