<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/14
 * Time: 11:09
 */

class Ws
{
    const HOST = '0.0.0.0';
    const PORT = 9501;

    private $server;

    public function __construct()
    {
        $this->server = new swoole_websocket_server(self::HOST, self::PORT);

        $this->server->set([
            'task_worker_num' => 2,
            'enable_static_handler' => true,
            'document_root' => '/mnt/htdocs/swoole-demo/data',
        ]);

        $this->server->on('open', [$this, 'onOpen']);
        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('task', [$this, 'onTask']);
        $this->server->on('finish', [$this, 'onFinish']);
        $this->server->on('close', [$this, 'onClose']);

        $this->server->start();
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
    }

    /**
     * 监听ws消息事件
     * @param $server
     * @param $frame
     */
    public function onMessage($server, $frame)
    {
        echo 'ser-push-message:' . $frame->data . "\n";
        $data = [
            'task' => 1,
            'fd' => $frame->fd
        ];
        $server->task($data);
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

$obj = new Ws();