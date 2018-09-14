<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/14
 * Time: 10:04
 */

$server = new swoole_websocket_server("0.0.0.0", 9501);

$server->set([
    'enable_static_handler' => true,
    'document_root' => '/mnt/htdocs/swoole-demo/data',
]);

$server->on('open', 'onOpen');

function onOpen($server, $request)
{
    print_r($request->fd);
}

$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "ws-push-success");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();