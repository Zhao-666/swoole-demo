<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/14
 * Time: 9:23
 */

$http = new swoole_http_server('0.0.0.0', 8811);

$http->set([
    'enable_static_handler' => true,
    'document_root' => '/mnt/htdocs/swoole-demo/data',

]);

$http->on('request', function ($request, $response) {
    $response->end(json_encode($request->header));
});

$http->start();