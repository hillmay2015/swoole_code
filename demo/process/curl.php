<?php
/**
 * User: may
 * Date: 2019/6/14
 * Time: 下午5:24
 */
echo "process-start-time" . date('Y-m-d H:i:s', time()) . PHP_EOL;
$wokers = [];
$urls = [
    'http://baidu.com',
    'http://wiki.swoole.com',
    'http://qq.com',
    'http://sina.com',
    'https://www.jd.com',
    'https://www.taobao.com'
];

//传统php 做法
//foreach ($urls as $url) {
//    $contents[] = file_get_contents($urls);
//}

//并不是 耗时6秒 而是1秒 6个进程同时进行
for ($i = 0; $i < 6; $i++) {
    $process = new swoole_process(function (swoole_process $worker) use ($i, $url) {
        $content = curlData($url[$i]);
        echo $content . PHP_EOL;
       // $worker->write($content);
    }, true); //true 就到管道 false到屏幕
    $pid = $process->start();
    $wokers[$pid] = $process;
}

foreach ($wokers as $process) {
    echo $process->read();
}


//获取url里面内容
function curlData($url)
{
    //file_get_contents();
    sleep(1);
    return $url . "sucess" . PHP_EOL;

}

echo "process-end-time" . date('Y-m-d H:i:s', time()) . PHP_EOL;