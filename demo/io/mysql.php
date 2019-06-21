<?php

/**
 * User: may
 * Date: 2019/6/12
 * Time: 下午2:18
 */
class AsyMysql
{
    public $db = "";
    public $dbConfig = [];

    public function __construct()
    {
        $this->db = new Swoole\Mysql;
        $this->dbConfig = [
            'host' => '127.0.0.1',
            'port' => '8889',
            'user' => 'root',
            'password' => 'root',
            'database' => 'gaoluokeji',
            'charset' => 'utf8',

        ];
    }

    public function insert()
    {

    }

    public function update()
    {

    }

    /**
     * @param $id
     * @param $username
     */
    public function excute($id, $username)
    {
        $this->db->connect($this->dbConfig, function ($db, $result) use ($id, $username) {
            echo 'mysql connect' . PHP_EOL;
            if ($result === false) {
                var_dump($db->connect_error);
            }
             $sql = "select * from admin where id=3";
           // $sql = "update admin set `username`='" . $username . "'where id=" . $id;
            $db->query($sql, function ($db, $result) {
                //select $result返回的是结果集
                //add update delete返回的是 bool值
                if ($result === false) {
                    var_dump($db->error);
                } elseif ($result === true) {
                    var_dump($db->affected_rows);

                } else {
                    echo '<pre>';
                    print_r($result);


                }
                $db->close;
            });
        });
        return true;

    }
}

$obj = new AsyMysql();
$flag = $obj->excute(10, "xym333");
var_dump($flag) . PHP_EOL;
echo 'start' . PHP_EOL;

//正常流程 应该是 先执行 实例化 然后查询 接着打印$flag 再打印start

//异步执行结果 是  先bool 再start 再数据库连接 再打印数组