<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/12
 * Time: 8:05
 */

//创建Server对象，监听 127.0.0.1:9501端口
$serv = new swoole_server("127.0.0.1", 9501);

$serv->set([
    'worker_num' => 8,//worker进程数 cpu 1-4倍
    'max_request' => 10000,
]);

//监听连接进入事件
$serv->on('connect', function ($serv, $fd, $reactor_id) {
    echo "Client: Connect.$fd.reactor.$reactor_id\n";
});

//监听数据接收事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "Server: " . $data . ' Reactor: ' . $from_id);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$serv->start();