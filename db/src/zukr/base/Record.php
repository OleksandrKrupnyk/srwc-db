<?php


namespace zukr\base;

/**
 * Class Record
 *
 * @package zukr\base
 */
abstract class Record implements RecordInterface
{
    /**
     * @var \MysqliDb
     */
    private $_db;
    private $_table;
    private $_actionSave;

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $primaryKeyId = static::getPrimaryKey();
        if ($this->{$primaryKeyId} === null) {
            $this->_actionSave = 'insert';
        } else {
            $this->_actionSave = 'update';
        }
        return true;
    }

    public function afterSave()
    {

    }

    public static function getPrimaryKey()
    {
        return 'id';
    }

    /**
     * @return \MysqliDb
     */
    public function getDb()
    {
        return $this->_db;
    }


    public function __construct()
    {
        $this->_db    = Base::$app->db;
        $this->_table = static::getTableName();
    }

    /**
     * @return string
     */
    public function getNameModel()
    {
        try {
            return (new \ReflectionClass($this))->getShortName();
        } catch (\ReflectionException $e) {
            var_dump($e->getMessage());
        }
    }

    public static function getTableName()
    {
        return \strtolower(basename(get_called_class()));
    }


    /**
     * @param      $arrayData
     * @param null $form
     */
    public function load(array $arrayData, $form = null)
    {
        $data = $arrayData;
        if ($form === null) {
            $form = $this->getNameModel();
            $data = $arrayData[$form];
        } elseif ($form === false) {
            $data = $arrayData;
        } elseif (\is_string($form)) {
            $data = $arrayData[$form];
        }

        if (!empty($data)) {
            foreach ($data as $field => $value) {
                $this->{$field} = $value;
            }
        }

    }

    /**
     * @param array|int $id
     * @return \MysqliDb|array
     * @throws \Exception
     */
    public function findById($id)
    {

        if (filter_var($id, FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY)) {
            return $this->_db
                ->where('id', $id, 'IN')
                ->get($this->_table);
        }

        if (filter_var($id, FILTER_VALIDATE_INT)) {
            return $this->_db->where('id', $id)
                ->getOne($this->_table);

        }

        throw new \InvalidArgumentException('Array or Int');

    }

    public function save()
    {
        if (!$this->beforeSave()) {
            return false;
        }
        call_user_func([static::class, $this->_actionSave]);


        $this->afterSave();
    }

    public static function find()
    {
        $className = get_called_class();
        return (new $className())->getDb();
    }

    /**
     * @return bool|string
     * @throws \Exception
     */
    protected function update()
    {
        $arrayAttributes = $this->setAttributes();
        $primaryKeyId    = static::getPrimaryKey();
        $this->_db->where($primaryKeyId, $this->{$primaryKeyId});
        $this->_db->update(static::getTableName(), $arrayAttributes);
        if ($this->_db->count > 0) {
            return true;
        }
        return $this->_db->getLastError();
    }

    /**
     * @return bool|string
     * @throws \Exception
     */
    protected function insert()
    {
        $arrayAttributes = $this->setAttributes();
        $id              = $this->_db->insert(static::getTableName(), $arrayAttributes);
        if ($id) {
            $primaryKeyId          = static::getPrimaryKey();
            $this->{$primaryKeyId} = $id;
            return true;
        }
        return $this->_db->getLastError();
    }

    protected function setAttributes()
    {
        $attributes = [];
        foreach ($this as $field => $value) {
            if ($value !== null && !is_object($value) && $field[0] !=='_') {
                if (is_string($value) && \mb_strtolower($value) === 'now') {
                    $attributes[$field] = $this->_db->now();
                } else {
                    $attributes[$field] = $value;
                }
            }

        }
        return $attributes;
    }

}