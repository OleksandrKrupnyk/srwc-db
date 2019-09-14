<?php


namespace zukr\base;


use Dotenv\Dotenv;
use MysqliDb as DB;

/**
 * Class App
 * @property \MysqliDb $db
 * @package zukr\base
 */
class App
{

    /**
     * @var \MysqliDb
     */
    private $_db;
    public $param;
    public $param2;

    public $__params = [];

    public function __get($name)
    {
        if ($name === 'db') {
            if ($this->_db === null) {
                $this->initDB();
            }
            return $this->_db;
        }
    }

    public function __set($name, $value)
    {
        if ($name === 'db' && $value instanceof DB) {
            $this->_db = $value;
        }
    }

    public function __isset($name)
    {
        if ($name === 'db') {
            return isset($this->_db);
        }
    }


    private function initDB()
    {
        Dotenv::create(__DIR__ . '/../../../')->load();
        $host      = getenv('DB_SERVER');
        $user      = getenv('DB_USER');
        $base      = getenv('DB_NAME');
        $pass      = getenv('DB_PASSWORD');
        $this->_db = new DB($host, $user, $pass, $base);

    }


}