<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/17
 * Time: 10:41
 */

$content = date('Y-m-d H:i:s') . PHP_EOL;
swoole_async_writefile(__DIR__ . '/1.txt', $content, function ($filename) {
    echo "filename: " . $filename . PHP_EOL;
}, FILE_APPEND);

echo "start" . PHP_EOL;