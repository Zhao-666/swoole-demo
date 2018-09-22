<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/22
 * Time: 9:54
 */

$http = new swoole_http_server('0.0.0.0', 8811);

$http->on('request', function ($request, $response) {
    $redis = new \Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 6379);
    $key = $redis->get('hello');
    echo $key;
});

$http->start();