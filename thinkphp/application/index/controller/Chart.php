<?php
namespace app\index\controller;

use app\common\lib\Util;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;


class Chart
{
    public function index()
    {
        //登录的一个判断
        if (empty($_POST['game_id']) || empty($_POST['content'])) {
            return Util::show(config('code.error'), 'error');
        }
        $data = [
            'user' => '用户' . rand(0, 2000),
            'content' => $_POST['content'],
        ];

        print_r($_POST);
        $clinets = Predis::getInstance()->sMembers(config('redis.live_game_key'));

        foreach ($clinets as $fd) {
            $_POST['http_server']->push($fd, json_encode($data));
        }

//        foreach ($_POST['http_server']->ports[1]->connections as $fd) { //代表8816端口 $fd用户的标识
//            $_POST['http_server']->push($fd, json_encode($data));
//        }

        return Util::show(config('code.success'), 'OK');
    }
}
