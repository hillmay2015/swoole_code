<?php
/**
 * User: may
 * Date: 2019/6/12
 * Time: 上午11:36
 */

//执行逻辑  1.先写文件  2 然后执行start  3 再执行回调函数
//代码运行后 先输出start  再输出success
//date_default_timezone_set("PRC");
$content = date("Y-m-d H:i:s",time()).PHP_EOL;
swoole_async_writefile(__DIR__ . "/1.log", $content, function ($filename) {
    echo 'success'. PHP_EOL;  //写入成功后 执行回调函数
},FILE_APPEND); //FILE_APPEND追加的方式写
echo "start" . PHP_EOL;


