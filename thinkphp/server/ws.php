<?php

/**
 * User: may
 * Date: 2019/6/19
 * Time: 上午11:33
 */
class Ws
{

    const HOST = "0.0.0.0";
    const PORT = 8815;
    const CHART_PORT = 8816;

    public $ws = null;

    public function __construct()
    {
        //先通过redis smembers获取是否有key
//        $members = app\common\lib\redis\Predis::getInstance()->sMembers(config('redis.live_game_key'));
//        if (!empty($members)) {
//            foreach ($members as $key) {
//                app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_game_key'), $key);
//            }
//        }

        $this->ws = new swoole_websocket_server (self::HOST, self::PORT);

        $this->ws->listen(self::HOST, self::CHART_PORT, SWOOLE_SOCK_TCP);

        $this->ws->set([
            'enable_static_handler' => true,
            'document_root' => "/Applications/MAMP/htdocs/swoole_code/thinkphp/public/static",
            'worker_num' => 4,
            'task_worker_num' => 4,
        ]);

        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("workerstart", [$this, 'onWorkerStart']);
        $this->ws->on("request", [$this, 'onRequest']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on("task", [$this, 'onTask']);
        $this->ws->on("finish", [$this, 'onFinish']);
        $this->ws->on("close", [$this, 'onClose']);

        $this->ws->start();
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request)
    {
        // var_dump($request->fd);
        // print_r($ws);
//        echo  $request->fd;
        $_POST['http_server'] = $ws;
        //fd放到redis里面去
        app\common\lib\redis\Predis::getInstance()->sAdd(config('redis.live_game_key'), $request->fd);
    }

    /**
     * @param $server
     * @param $worker_id
     */
    public function onWorkerStart($server, $worker_id)
    {
        //定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');
        // 加载框架引导文件
        // require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../thinkphp/start.php';


    }

    /**
     * @param $request
     * @param $response
     * request回调
     */
    public function onRequest($request, $response)
    {
        $_SERVER = [];
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
        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        $_GET = [];
        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }

        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }
        $_POST['http_server'] = $this->ws;
        ob_start();

        try {// 执行应用并响应
            think\Container::get('app', [APP_PATH])
                ->run()
                ->send();
        } catch (\Exception $e) {
        }

        $res = ob_get_contents();
        ob_end_clean();

        $response->end($res); //数据要显示到浏览器 需要把数据放在end 方法

    }


    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame)
    {
        echo "sever-push-message:{$frame->data}\n";//来自客户端发的数据
        $ws->push($frame->fd, "server-push:" . "sever data " . date("Y-m-d H:i:s", time()));//server推送数据给客户端
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     */
    public function onTask($ws, $taskId, $workerId, $data)
    {
        //分发task机制 让不同的任务能走不同的逻辑
        $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        $flag = $obj->$method($data['data'], $ws);

        return true;//告诉worker
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $data onTask方法return的值
     */
    public function onFinish($ws, $taskId, $data)
    {
        echo "taskId:{$taskId}\n";
        echo "finish-data-success:{$data}\n";

    }

    /**
     *
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd)
    {
        echo "closed client_id:{$fd}\n";
        //从redis里删除用户
        app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_game_key'), $fd);
    }

}

new Ws();