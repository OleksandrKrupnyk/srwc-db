<?php


namespace zukr\base;

/**
 * Class Record
 *
 * @package zukr\base
 */
abstract class Record
{
    /**
     * @var \MysqliDb
     */
    private $_db;
    private $_table;

    /**
     * @return \MysqliDb
     */
    public function getDb()
    {
        return $this->_db;
    }


    public function __construct()
    {
        $this->_db = Base::$app->db;
        $this->_table = self::getTableName();
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
    public function load($arrayData, $form = null)
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

        foreach ($data as $field => $value) {
            $this->{$field} = $value;
        }

    }

    /**
     * @param array|int $id
     * @return \MysqliDb|array
     * @throws \Exception
     */
    public function findById($id)
    {

        if (filter_var($id, FILTER_VALIDATE_INT,FILTER_REQUIRE_ARRAY )) {
            return $this->_db
                ->where('id', $id, 'IN')
                ->get($this->_table);
        }

        if (filter_var($id,FILTER_VALIDATE_INT)) {
            return $this->_db->where('id', $id)
                    ->getOne($this->_table);

        }

        throw new \InvalidArgumentException('Array or Int');

    }

    public static function find()
    {
        $className = get_called_class();
        return (new $className())->getDb();
    }

}