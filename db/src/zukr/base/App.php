<?php


namespace zukr\base;


use Dotenv\Dotenv;
use MysqliDb as DB;
use Stash\Driver\FileSystem;
use Stash\Pool;

/**
 * Class App
 *
 * @property \MysqliDb $db
 * @package zukr\base
 */
class App
{

    const TTL = 600;
    private static $obj;
    /**
     * @var \MysqliDb
     */
    private $_db;
    /**
     * @var
     */
    private $_cache;
    public  $param;
    public  $param2;

    private $_ttl;
    public  $__params = [];

    private $isCached;

    private function __construct()
    {
        Dotenv::create(__DIR__ . '/../../../')->load();
        $this->isCached = getenv('CACHE');
        $this->isCached = $this->isCached ?? false;

        $this->_ttl = (int) getenv('CACHE_TTL');
        $this->_ttl = $this->_ttl ?? self::TTL;
        $driver = new FileSystem([
            'path' => 'c:\\temp1'
        ]);
        $this->_cache = new Pool($driver);
        $this->initDB();
    }


    public static function getInstance()
    {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return self::$obj;
    }

    public function __get($name)
    {
        if ($name === 'db') {
            if ($this->_db === null) {
                $this->initDB();
            }
            return $this->_db;
        } elseif ('cache' === $name) {
            return $this->_cache;
        }
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if ($name === 'db' && $value instanceof DB) {
            $this->_db = $value;
        } elseif ('cache' === $name && $value instanceof Pool) {
            $this->_cache = $value;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        if ($name === 'db') {
            return isset($this->_db);
        }
    }

    /**
     *
     */
    private function initDB()
    {

        $host = getenv('DB_SERVER');
        $user = getenv('DB_USER');
        $base = getenv('DB_NAME');
        $pass = getenv('DB_PASSWORD');
        $this->_db = new DB($host, $user, $pass, $base);

    }

    /**
     * @param      $key
     * @param      $func
     * @param null $ttl
     * @return mixed
     */
    public function cacheGetOrSet($key, $func, $ttl = null)
    {
        $ttl = $ttl ?? $this->_ttl;
        $item = $this->_cache->getItem($key);
        if ($item->isMiss()) {
            if ($func instanceof \Closure) {
                $data = $func();
            } else {
                $data = $func;
            }

            $item->setTTL($ttl);
            $this->_cache->save($item->set($data));
            return $data;

        }
        return $item->get();
    }


    public function cacheFlush(): void
    {
        $this->_cache->clear();
    }


}