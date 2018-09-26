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

$http->on('WorkerStart', function (swoole_http_server $server, $workerId) {
    echo 'worker_start-' . $workerId . PHP_EOL;

    // 加载基础文件
    require __DIR__ . '/../thinkphp/base.php';

});

$http->on('request', function ($request, $response) use ($http) {
//    $_SERVER = [];
    if (isset($request->server)) {
        foreach ($request->server as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    if (isset($request->header)) {
        foreach ($request->header as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    $_GET = [];
    if (isset($request->get)) {
        foreach ($request->get as $k => $v) {
            $_GET[$k] = $v;
        }
    }
    $_POST = [];
    if (isset($request->post)) {
        foreach ($request->post as $k => $v) {
            $_POST[$k] = $v;
        }
    }
    ob_start();
    try {
        \think\Container::get('app')->run()->send();
    } catch (Exception $e) {
    }
//    echo 'action-' . request()->action() . PHP_EOL;
    $ret = ob_get_contents();
    ob_end_clean();
    $response->end($ret);
//    $http->close();
});

$http->start();