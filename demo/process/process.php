<?php
/**
 * User: may
 * Date: 2019/6/13
 * Time: 上午9:41
 */

$process = new swoole_process(function (swoole_process $pro) {
    echo '1111';
    //php redis.php  写完整路径
    $pro->exec("/usr/bin/php", [__DIR__ . '../server/http_server.php']); //先开启子进程 子进程中开启http_server
}, true); //true 就到管道 false到屏幕

$pid = $process->start();
echo $pid . PHP_EOL;

swoole_process::wait();//当结束时  回收结束运行 的子进程