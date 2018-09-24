<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/14
 * Time: 9:23
 */

$http = new swoole_http_server('0.0.0.0', 8811);

$http->set([
    'worker_num' => 8,//worker进程数 cpu 1-4倍
    'enable_static_handler' => true,
    'document_root' => '/mnt/htdocs/swoole-demo/Live/public/static',
]);

$http->on('request', function ($request, $response) {
    $response->end(json_encode($request->header));
});

$http->start();