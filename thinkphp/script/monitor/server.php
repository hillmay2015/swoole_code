<?php

/**
 * 监控服务 8815 ws.php
 * User: may
 * Date: 2019/6/20
 * Time: 下午1:25
 */

// nohup php server.php >/Applications/MAMP/htdocs/swoole_code/thinkphp/script/monitor/a.txt

//后台执行 脚本运行

class Server
{
    const PORT = 8815;

    public function port()
    {
        $shell = "netstat -an| grep  " . self::PORT . " |grep LISTEN |wc -l";
        // echo $shell;
        $result = shell_exec($shell);
        // echo $result;exit();
        if ($result != 1) {
            //发送报警 邮件 短信 都可以
            echo date('Y-m-d H:i:s') . 'error' . PHP_EOL;
        } else {
            echo date('Y-m-d H:i:s') . 'success' . PHP_EOL;

        }
    }


}

//每2秒钟去执行

swoole_timer_tick(2000, function ($timer_id) {
    echo 'timer-start' . PHP_EOL;
    (new Server())->port();
});

//检测硬盘 df -h

//监测接口的状态

