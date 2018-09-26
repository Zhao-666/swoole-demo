<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/26
 * Time: 23:18
 */

class Http
{
    const HOST = '0.0.0.0';
    const PORT = 9501;

    private $server;

    public function __construct()
    {
        $this->server = new swoole_http_server(self::HOST, self::PORT);

        $this->server->set([
            'worker_num' => 4,
            'task_worker_num' => 2,
            'enable_static_handler' => true,
            'document_root' => '/mnt/htdocs/swoole-demo/Live/public/static',
        ]);

        $this->server->on('workerStart', [$this, 'onWorkerStart']);
        $this->server->on('request', [$this, 'onRequest']);
        $this->server->on('open', [$this, 'onOpen']);
        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('task', [$this, 'onTask']);
        $this->server->on('finish', [$this, 'onFinish']);
        $this->server->on('close', [$this, 'onClose']);

        $this->server->start();
    }

    public function onWorkerStart($server, $workerId)
    {
        echo 'worker_start-' . $workerId . PHP_EOL;

        // 加载基础文件
        require __DIR__ . '/../thinkphp/base.php';
    }

    public function onRequest($request, $response)
    {
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
    }

    public function onTask($server, $taskId, $workerId, $data)
    {
        print_r($data);
        sleep(5);
        return 'on task finish';
    }

    public function onFinish($server, $taskId, $data)
    {
        echo 'taskId: ' . $taskId . PHP_EOL;
        echo 'finish data success ' . $data . PHP_EOL;
    }

    /**
     * 监听ws连接事件
     * @param $server
     * @param $request
     */
    public function onOpen($server, $request)
    {
        var_dump($request->fd);
        if ($request->fd == 1) {
            swoole_timer_tick(2000, function ($timerId) {
                echo '2s: timerId ' . $timerId . "\n";
            });
        }
    }

    /**
     * 监听ws消息事件
     * @param $server
     * @param $frame
     */
    public function onMessage($server, $frame)
    {
        echo 'ser-push-message:' . $frame->data . "\n";
//        $data = [
//            'task' => 1,
//            'fd' => $frame->fd
//        ];
//        $server->task($data);
        swoole_timer_after(5000, function () use ($server, $frame) {
            echo "5s-after\n";
            $server->push($frame->fd, "server-timer-after:");
        });
        $server->push($frame->fd, 'server-push:' . date('Y-m-d H:i:s'));
    }

    /**
     * close
     * @param $server
     * @param $fd
     */
    public function onClose($server, $fd)
    {
        echo 'clientid:' . $fd . "\n";
    }

}

new Http();