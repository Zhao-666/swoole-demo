<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/17
 * Time: 10:13
 */

swoole_async_readfile(__DIR__ . '/1.txt', function ($filename, $fileContent) {
    echo "filename: " . $filename . PHP_EOL;
    echo 'content: ' . $fileContent . PHP_EOL;
});

echo "start" . PHP_EOL;