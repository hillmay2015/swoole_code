<?php

/**
 * User: may
 * Date: 2019/6/18
 * Time: 下午3:10
 */
class Http
{
    const HOST = "0.0.0.0";
    const PORT = 8815;

    public $http = null;

    public function __construct()
    {
        $this->http = new swoole_http_server (self::HOST, self::PORT);

        $this->http->set([
            'enable_static_handler' => true,
            'document_root' => "/Applications/MAMP/htdocs/swoole_code/thinkphp/public/static",
            'worker_num' => 4,
            'task_worker_num' => 4,
        ]);

        $this->http->on("workerstart", [$this, 'onWorkerStart']);
        $this->http->on("request", [$this, 'onRequest']);
        $this->http->on("task", [$this, 'onTask']);
        $this->http->on("finish", [$this, 'onFinish']);
        $this->http->on("close", [$this, 'onClose']);

        $this->http->start();
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
        $_POST['http_server'] = $this->http;
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
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     */
    public function onTask($http, $taskId, $workerId, $data)
    {
        //分发task机制 让不同的任务能走不同的逻辑

        $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        $flag = $obj->$method($data['data']);

        return true;//告诉worker
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $data onTask方法return的值
     */
    public function onFinish($http, $taskId, $data)
    {
        echo "taskId:{$taskId}\n";
        echo "finish-data-success:{$data}\n";

    }

    /**
     *
     * @param $http
     * @param $fd
     */
    public function onClose($http, $fd)
    {
        echo "closed client_id:{$fd}\n";
    }


}

new Http();
