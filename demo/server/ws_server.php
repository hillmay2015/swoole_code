<?php
/**
 * User: may
 * Date: 2019/6/5
 * Time: 下午2:15
 */
$server = new swoole_websocket_server ("0.0.0.0", 8813);

$server->set(
    [
        'enable_static_handler'=>true,
        'document_root'=>"/Applications/MAMP/htdocs/swoole_code/data/",

    ]);

//监听websocket 连接打开事件
$server->on('open', 'onOpen');
function onOpen($server,$request){
    print_r($request->fd);

}

//监听websocket 消息事件
/**$fd 客户端连接的ID，如果指定的$fd对应的TCP连接并非websocket客户端，将会发送失败
$data 要发送的数据内容
$opcode，指定发送数据内容的格式，默认为文本。发送二进制内容$opcode参数需要设置
 * */
$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd,  "hello websocket server push success"); //把数据push到客户端
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();