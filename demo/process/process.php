<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/22
 * Time: 9:02
 */

$process = new swoole_process(function (swoole_process $process) {
    $process->exec('/usr/local/php/bin/php', [
        __DIR__ . '/../server/http_server.php'
    ]);
}, true);

$pid = $process->start();

var_dump($pid);

swoole_process::wait();