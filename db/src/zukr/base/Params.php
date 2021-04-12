<?php


namespace zukr\base;

use ArrayAccess;
use Countable;
use Iterator;
use Serializable;
use zukr\base\helpers\ArrayHelper;

/**
 * Class Params
 *
 * @property string $ALLOW_EMAIL
 * @property string $INVITATION
 * @property string $PRINT_DDTU_HEADER
 * @property string $SHOW_DB_TABLE
 * @property string $SHOW_FILES_LINK
 * @property string $SHOW_PROGRAMA
 * @property string $SHOW_RAITING
 * @property string $MARK_INVITE_WORKS
 * @property string $DENNY_EDIT_REVIEW
 * @property string $DATEPL
 * @property string $DATEPO
 * @property string $N_AUTORS
 * @property string $N_LEADERS
 * @property string $NYEARS
 * @property string $ORDERPL
 * @property string $ORDERPO
 * @property string $YEAR
 *
 * @package zukr\base
 */
class Params implements ArrayAccess, Countable, Iterator, Serializable
{

    const TURN_ON  = '1';
    const TURN_OFF = '0';
    /**
     * Дозволені імена параметрів
     */
    const PARAMS = [
        'ALLOW_EMAIL',
        'DATEPL',
        'DATEPO',
        'DENNY_EDIT_REVIEW',
        'FILL_EMPTY_GUID',
        'INVITATION',
        'MARK_INVITE_WORKS',
        'NYEARS',
        'N_AUTORS',
        'N_LEADERS',
        'ORDERPL',
        'ORDERPO',
        'PRINT_DDTU_HEADER',
        'SHOW_DB_TABLE',
        'SHOW_FILES_LINK',
        'SHOW_PROGRAMA',
        'SHOW_RAITING',
        'YEAR',
    ];
    /**
     * @var Params
     */
    private static $obj;
    /**
     * @var int
     */
    protected $_position = 0;
    /**
     * @var \MysqliDb
     */
    private $db;
    /**
     * @var array
     */
    private $_container;

    /**
     * Params constructor.
     */
    private function __construct()
    {
        $this->db = Base::$app->db;
        $this->_container = $this->db
            ->map('parametr')
            ->get(self::tableName());
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'settings';
    }

    /**
     * @return Params
     */
    public static function getInstance(): self
    {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return self::$obj;
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->_container[$offset]['value'] : null;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->_container[$offset]);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->_container[] = $value;
        } else {
            $this->_container[$offset]['value'] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_container[$offset]);
    }

    /**
     *
     */
    public function rewind()
    {
        $this->_position = 0;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->_container[$this->_position];
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->_position;
    }

    /**
     *
     */
    public function next()
    {
        ++$this->_position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->_container[$this->_position]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->_container);
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return \serialize($this->_container);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
        $this->_container = \unserialize($data);
    }

    /**
     * @param array|null $data
     * @return array
     */
    public function __invoke(array $data = null)
    {
        if ($data === null) {
            return $this->_container;
        }

        $this->_container = $data;
        return [];
    }

    /**
     * @param $name
     * @return mixed|string
     */
    public function __get($name)
    {
        return isset($this->_container[$name]) ? $this->_container[$name]['value'] : 'not set';
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $keys = \array_keys($this->_container);
        if (\in_array($name, $keys)) {
            $this->_container[$name]['value'] = $value;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_container[$name]);
    }

    /**
     * @return array
     */
    public function getContainer(): ?array
    {
        return $this->_container;
    }

    /**
     * Список значень налаштувань індексований за назвою параметра
     *
     * @return array Список налаштувань
     */
    public function getAllsettingValue(): ?array
    {
        return ArrayHelper::map($this->getContainer(), 'parametr', 'value');
    }

}