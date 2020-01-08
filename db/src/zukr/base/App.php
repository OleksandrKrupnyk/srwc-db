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
 * @property string $_snrcrf
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
    /**
     * @var int Час кешування
     */
    private $_ttl;

    private $isCached;
    /**
     * @var string Шлях для теки кешування
     */
    private $cachePath;
    /**
     * @var string
     */
    private $_snrcrf;

    /**
     * App constructor.
     */
    private function __construct()
    {
        Dotenv::create(__DIR__ . '/../../../')->load();
        $this->setAppName(\getenv('APP_NAME'));
        $this->isCached = \getenv('CACHE');
        $this->isCached = $this->isCached ?? false;
        $this->cachePath = \getenv('CACHE_PATH') ?? '/tmp';

        $this->_ttl = (int)\getenv('CACHE_TTL');
        $this->_ttl = $this->_ttl ?? self::TTL;

        $driver = new FileSystem([
            'path' => $this->cachePath
        ]);

        $driverRedis = new Redis([
            'servers' => [
                [
                    'server' => \getenv('REDIS_SERVER') ?? '127.0.0.1',
                    'port' => \getenv('RD_PORT') ?? '6379',
                    'ttl' => $this->_ttl]
            ]
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
        if ('_snrcrf' === $name) {
            return $this->_snrcrf;
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
        } elseif ('_snrcrf' === $name && is_string($value)) {
            $this->_snrcrf = $value;
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

        $host = \getenv('DB_SERVER') ?? '127.0.0.1';
        $user = \getenv('DB_USER') ?? 'root';
        $base = \getenv('DB_NAME') ?? 'root';
        $pass = \getenv('DB_PASSWORD') ?? 'test';
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
            ? 'Zukr ' . \date('Y')
            : $app_name;
    }


}