<?php
/**
 * User: may
 * Date: 2019/6/14
 * Time: 下午5:45
 * 创建内存表
 */


//使用场景 数据共享的时候 可以 这样操作   10个子进程 需要共享数据
$table = new swoole_table(1024); //1024行

//内存表怎加一列
$table->column('id', $table::TYPE_INT,4);
$table->column('name', $table::TYPE_STRING,64);
$table->column('age', $table::TYPE_INT,3);
$table->create();

$table->set('hello', [
    'id' => 1,
    'name' => 'hello',
    'age' => '22'
]);

print_r($table->get('hello'));