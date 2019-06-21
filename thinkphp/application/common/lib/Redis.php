<?php
/**
 * User: may
 * Date: 2019/6/17
 * Time: 下午5:08
 */

namespace app\common\lib;

class Redis
{
    /**
     * @var string
     * 验证码redis key前缀
     */
    public static $pre = "sms_";
    /**
     * @var string
     * 用户user
     */
    public static $userpre = "user_";

    /**
     * @param $phone
     * @return string
     * 存储验证码
     */
    public static function smsKey($phone)
    {
        return self::$pre . $phone;
    }

    /**
     * @param $phone
     * @return string
     * 存储用户号信息
     */
    public static function userKey($phone)
    {
        return self::$userpre . $phone;

    }

    //优化点 很多框架加载都可以这么操作
//    public function __call($name, $arguments)
//    {
//        // TODO: Implement __call() method.
//    }
}