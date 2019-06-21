<?php
/**
 * User: may
 * Date: 2019/6/19
 * Time: 下午2:40
 */
namespace app\admin\controller;

use app\common\lib\Util;
use app\common\lib\redis\Predis;

class Live
{
    public function push()
    {
        // print_r($_GET);
        //获取在线的链接的用户
        if (empty($_GET)) {
            return Util::show(config('code.error'), 'error');//admin 这个是管理后台
            //token 数据校验
            //查询数据库 球队信息 图片


        }
        $teams = [
            '1' => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',],
            '4' => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png',],

        ];
        $data = ['type' => intval($_GET['type']),
            'title' => !empty($teams[$_GET['team_id']]['name']) ? $teams[$_GET['team_id']]['name'] : '直播员',
            'logo' => !empty($teams[$_GET['team_id']]['logo']) ? $teams[$_GET['team_id']]['logo'] : '',
            'content' => !empty($_GET['content']) ? $_GET['content'] : '',
            'image' => !empty($_GET['image']) ? $_GET['image'] : '',
        ];

        //赛况基本信息入库 拿到所有的用户的id
        // $_POST['http_server']->push(7, 'hello-xym-push-data:');


        $taskData = ['method' => 'pushLive',
            'data' => $data];

        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'), 'OK');
        //push到直播页面
    }

}