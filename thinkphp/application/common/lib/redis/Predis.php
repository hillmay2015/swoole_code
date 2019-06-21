<?php
/**
 * User: may
 * Date: 2019/6/17
 * Time: 下午5:08
 * 单例模式 请求多次 但是只连接一次
 */

namespace app\common\lib\redis;

class Predis
{
    public $redis = "";
    /**
     * @var
     * 定义单例模式的变量
     */
    private static $_instance = null;

    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();//只实例化一次 只连接一次
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->redis = new \Redis();
        $result = $this->redis->connect(config('redis.host'), config('redis.port'), config('redis.timeOut'));
        $this->redis->select(1);//选择1号redis数据库
        if ($result === false) {
            throw new \Exception('redis connect error');
        }
    }

    /**
     * @param $key
     * @param $value
     * @param int $expire
     * @return bool|string
     */
    public function set($key, $value, $expire = 0)
    {
        if (!$key) {
            return '';
        }
        if (is_array($value)) {
            $value = json_encode($value);
        }
        if (!$expire) {//未设置缓存时间
            return $this->redis->set($key, $value);
        }
        return $this->redis->setex($key, $expire, $value);
    }

    /**
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        if (!$key) {
            return '';
        }
        return $this->redis->get($key);
    }

    /**
     * @param $key
     * @param $value
     * @return int
     * 操作添加有序集合
     */
    public function sAdd($key, $value)
    {
        return $this->redis->sAdd($key, $value);
    }

    /**
     * @param $key
     * @param $value
     * @return int
     * 操作删除有序集合元素
     */
    public function sRem($key, $value)
    {
        return $this->redis->sRem($key, $value);
    }

    /**
     * @param $key
     * @return int
     * 操作删除有序集合元素
     */
    public function sMembers($key)
    {
        return $this->redis->sMembers($key);
    }

    /**
     * @return bool
     * 清空redis数据库连接
     */
    public function flushDB($db)
    {
        return $this->redis->flushDB($db);
    }
}