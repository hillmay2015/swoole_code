<?php
/**
 * redis配置文件
 * User: may
 * Date: 2019/6/17
 * Time: 下午4:55
 */


return [
    'host' => '127.0.0.1',
    'port' => '6379',
    'out_time' => 120,//缓存时间2分钟
    'timeOut' => 5,//连接超时时间5s
    'live_game_key'=>'live_game_key',//直播的key
];