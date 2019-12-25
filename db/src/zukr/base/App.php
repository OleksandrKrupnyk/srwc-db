<?php


namespace zukr\base;


use Dotenv\Dotenv;
use MysqliDb as DB;
use Stash\Driver\FileSystem;
use Stash\Driver\Redis;
use Stash\Pool;

/**
 * Class App
 *
 * @property string app_name
 * @property DB     $db
 * @package zukr\base
 */
class App
{
    /** @var int  Час кешування */
    const TTL = 600;
    /** @var App */
    private static $obj;
    /**
     * @var DB
     */
    private $_db;
    /**
     * @var Pool
     */
    private $_cache;
    private $_app_name;
    public  $param;
    public  $param2;

    private $_ttl;
    public  $__params = [];

    private $isCached;

    /**
     * App constructor.
     */
    private function __construct()
    {
        Dotenv::create(__DIR__ . '/../../../')->load();
        $this->setAppName(getenv('APP_NAME'));
        $this->isCached = getenv('CACHE');
        $this->isCached = $this->isCached ?? false;

        $this->_ttl = (int)getenv('CACHE_TTL');
        $this->_ttl = $this->_ttl ?? self::TTL;
        $driver = new FileSystem([
            'path' => 'c:\\temp1'
        ]);
        $driverRedis = new Redis([
            'servers' => [['server' => '127.0.0.1', 'port' => '6379', 'ttl' => $this->_ttl]]
        ]);
        $this->_cache = new Pool($driverRedis);
        $this->initDB();
    }


    /**
     * @return App
     */
    public static function getInstance()
    {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return self::$obj;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'db') {
            if ($this->_db === null) {
                $this->initDB();
            }
            return $this->_db;
        }

        if ('cache' === $name) {
            return $this->_cache;
        }

        if ('app_name' === $name) {
            return '&quot;' . $this->_app_name . '&quot;&copy;';
        }
        return null;
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
        return null;
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

    /**
     * Очищення кешу
     */
    public function cacheFlush(): void
    {
        $this->_cache->clear();
    }

    /**
     * @param array|false|string $app_name
     */
    private function setAppName($app_name): void
    {
        $this->_app_name = empty($app_name)
            ? 'Zukr '. date('Y')
            : $app_name;
    }


}