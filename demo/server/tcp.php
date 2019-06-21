<?php
/**
 * User: may
 * Date: 2019/6/4
 * Time: 下午2:52
 */
//创建server对象 监听127.0.0.1
$serv=new swoole_server("127.0.0.1","9501");

$serv->set([
    'reactor_num' => 111, //reactor thread num
    'worker_num' => 8,    //worker process num 进程数 建议等于开启cpu核数的1-4倍
    'max_request' => 10000, //每个进程处理的最大用户数

]);
//监听进入事件
$serv->on('connect',function ($serv,$fd,$reactor_id){
    echo "Client:{$reactor_id}-{$fd}Connet!!.\n";
});

//监听数据接收事件
/**
 * $fd客户端链接的唯一标识
 */
$serv->on('receive',function ($serv,$fd,$reactor_id,$data){
    $serv->send($fd,"server:{$reactor_id}-{$fd}".$data);
});


//监听连接关闭事件
$serv->on('close',function ($serv,$fd){
   echo 'Client:Close.\n';
});


//启动服务器
$serv->start();
