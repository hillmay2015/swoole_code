<?php
/**
 * User: may
 * Date: 2019/6/5
 * Time: 上午11:18
 */
$http = new swoole_http_server("127.0.0.1", 8811);//0.0.0.0表示监听所有地址

$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/Applications/MAMP/htdocs/swoole_code/data/",

    ]);

$http->on('request', function ($request, $response) {
    print_r($request->get);
    //有请求过来 可以 记录日志
    $content = [
        'date:' => date("Y-m-d H:i:s", time()),
        'get:' => $request->get,
        'post:' => $request->post,
        'header:' => $request->header,
    ];
    swoole_async_writefile(__DIR__ . "/access.log", json_encode($content).PHP_EOL,
        function ($filename) {
           //todo
        },
        FILE_APPEND);
    $response->cookie("admin_name", "ssss", time() + 1800);
    $response->end("<h1>http server Data :</h1>" . json_encode($request->get)); //数据要显示到浏览器 需要把数据放在end 方法

});

$http->start();