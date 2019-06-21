<?php
namespace app\index\controller;

use app\common\lib\ali\Sms;
use app\common\lib\Util;
use app\common\lib\Redis;


class Send
{
    public function index()
    {
        $phoneNum = request()->get('phone_num', 0, 'intval');
        if (empty($phoneNum)) {
            return Util::show(config('code.error'), 'error');
        }


        //生成一个随机数
        $code = rand(1000, 9999);

        $taskData = ['method' => 'sendSms',
            'data' => [
                'phone' => $phoneNum,
                'code' => $code,
            ]];
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'), 'OK');


    }
}
