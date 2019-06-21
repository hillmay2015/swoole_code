<?php
namespace app\index\controller;

use app\common\lib\ali\Sms;

class Index
{
    /**
     * 发送验证码
     */
    public function send()
    {
        echo '1212';
    }

    public function index()
    {
        return '';
    }

    public function test()
    {
        print_r($_GET);
        echo 'test' . time();
    }
}
