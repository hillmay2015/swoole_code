<?php
/**
 * User: may
 * Date: 2019/6/12
 * Time: 上午11:21
 * 异步读取文件
 *
 */

$result = swoole_async_readfile(__DIR__ . "/1.txt", function ($filename, $fileContent) { //最大读取4M数据
    echo "filename:" . $filename . PHP_EOL;  //PHP_EOL换行 根据不同操作系统来   __DIR__获取当前文件目录
    echo "content:" . $fileContent . PHP_EOL;
});

$result2 = Swoole::Async(__DIR__ . "/1.txt", function ($filename, $fileContent) {
    echo "filename:" . $filename . PHP_EOL;  //PHP_EOL换行 根据不同操作系统来   __DIR__获取当前文件目录
    echo "content:" . $fileContent . PHP_EOL;
});

var_dump($result);
echo "start" . PHP_EOL;


//场景  文件较大时  使用swoole_async_read()分开读取