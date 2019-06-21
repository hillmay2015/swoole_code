<?php
/**
 * User: may
 * Date: 2019/6/12
 * Time: 下午3:55
 * 异步执行redis
 */
$redisClient = new swoole_redis();
$redisClient->connect('127.0.0.1', 6379, function (swoole_redis $redisClient, $result) {
    echo "connect" . PHP_EOL;
    var_dump($result);
    $redisClient->set('hello', time(), function (swoole_redis $redisClient, $result) {
        var_dump($result);
    });

    $redisClient->get('hello', function (swoole_redis $redisClient, $result) {
        var_dump($result);
        $redisClient->close();
    });
//打印出全部key
    $redisClient->keys('*', function (swoole_redis $redisClient, $result) {
        var_dump($result);
        $redisClient->close();
    });

    //模糊匹配
    $redisClient->keys('*yp*', function (swoole_redis $redisClient, $result) {
        var_dump($result);
        $redisClient->close();
    });

});

echo 'start' . PHP_EOL;