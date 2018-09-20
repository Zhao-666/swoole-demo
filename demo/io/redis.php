<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/20
 * Time: 23:47
 */

$redisCli = new swoole_redis();
$redisCli->connect('127.0.0.1', 6379, function (swoole_redis $redisCli, $result) {
    echo 'connect' . PHP_EOL;
    var_dump($result);
    $redisCli->set('hello', 'world', function (swoole_redis $redisCli, $result) {
        echo 'set' . PHP_EOL;
        var_dump($result);
    });
});