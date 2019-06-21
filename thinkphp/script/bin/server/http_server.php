<?php
/**
 * User: may
 * Date: 2019/6/5
 * Time: 上午11:18
 */
$http = new swoole_http_server("127.0.0.1", 8815);//0.0.0.0表示监听所有地址

$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/Applications/MAMP/htdocs/swoole_code/thinkphp/public/static",
        'worker_number' => 5,
    ]);

$http->on('WorkerStart', function (swoole_server $server, $worker_id) {//$worker_id进程id
// 定义应用目录
    define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件
    require __DIR__ . '/../thinkphp/base.php';
    //require __DIR__ . '/../thinkphp/start.php';

});
$http->on('request', function ($request, $response) use ($http) {
  //  print_r($request->server);
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

    ob_start();

    try {// 执行应用并响应
        think\Container::get('app', [APP_PATH])
            ->run()
            ->send();
    } catch (\Exception $e) {
    }
   // echo '---action---:' . request()->action();
    $res = ob_get_contents();
    ob_end_clean();

    $response->end($res); //数据要显示到浏览器 需要把数据放在end 方法
     // $http->close();
});

$http->start();