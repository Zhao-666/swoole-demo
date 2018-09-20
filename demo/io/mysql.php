<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/9/17
 * Time: 10:54
 */

class AysMysql
{
    private $db = "";

    private $dbConfig = [];

    public function __construct()
    {
        $this->db = new swoole_mysql();
        $this->dbConfig = [
            'host' => '192.168.33.2',
            'port' => 3306,
            'user' => 'root',
            'password' => '123123',
            'database' => 'swoole',
            'charset' => 'utf8'
        ];
    }

    public function update()
    {

    }

    public function add()
    {

    }

    public function execute($id, $username)
    {
        $this->db->connect($this->dbConfig, function ($db, $result) {
            if (!$result) {
                var_dump($db->connect_error);
            }

            $sql = "select * from test where id=1";
            $db->query($sql, function ($db, $result) {
                var_dump($result);
            });
        });
        return true;
    }
}

$db = new AysMysql();
$db->execute(1, 'qwe');