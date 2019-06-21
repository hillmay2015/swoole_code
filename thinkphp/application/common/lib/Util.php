<?php
/**
 * User: may
 * Date: 2019/6/17
 * Time: 下午4:22
 */
namespace app\common\lib;
class Util
{
    /**
     * @param $status
     * @param $msg
     * @param $data
     * Api输出格式
     */
    public static function show($status, $msg = '', $data = [])
    {
        $result = [
            'status' => $status,
            'message' => $msg,
            'data' => $data,
        ];
        echo json_encode($result);
    }
}