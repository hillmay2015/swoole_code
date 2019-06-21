<?php
/**
 * swoole 后续所有的task异步任务
 * User: may
 * Date: 2019/6/19
 * Time: 上午9:20
 */
namespace app\common\lib\task;

use app\common\lib\ali\Sms;
use app\common\lib\redis\Predis;
use app\common\lib\Util;
use app\common\lib\Redis;

class Task
{
    /**
     * @param $data
     * @param $serv swoole server对象
     * @return bool
     * 通过task发送短信验证码
     */
    public function sendSms($data, $serv)
    {
        try {
            $response = Sms::sendSms($data['phone'], $data['code']);
        } catch (\Exception $e) {
            return false;
        }
        //如果发送成功 把验证码记录到redis
        if ($response->Code === "OK") {
            Predis::getInstance()->set(Redis::smsKey($data['phone']), $data['code'], config('redis.out_time'));
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @param $serv swoole server对象
     * 通过task机制发送赛况数据给客户端
     */
    public function pushLive($data, $serv)
    {
        $clinets = Predis::getInstance()->sMembers(config('redis.live_game_key'));

        foreach ($clinets as $fd) {
            $serv->push($fd, json_encode($data));
        }
    }
}