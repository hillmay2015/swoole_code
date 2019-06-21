<?php
namespace app\index\controller;

use app\common\lib\Util;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;


class Login
{
    public function index()
    {
        //获取手机号 验证码

        $phoneNum = intval($_GET['phone_num']);
        $code = intval($_GET['code']);
        if (empty($phoneNum) || empty($code)) {
            return Util::show(config('code.error'), 'phone or code error');
        }

        //redis里查看code是否存在 且时间一致  则允许登录
        $redisCode = Predis::getInstance()->get(Redis::smsKey($phoneNum));



        if($redisCode==$code){
            //写入redis
            $data=[
                'user'=>$phoneNum,
                'srcKey'=>md5(Redis::userKey($phoneNum)),
                'time'=>time(),
                'isLogin'=>true,
            ];
            Predis::getInstance()->set(Redis::userKey($phoneNum),$data);
            return Util::show(config('code.success'), 'ok',$data);
        }else{
            return Util::show(config('code.error'), 'login error');
        }
        //redis.so同步来实现



    }
}
