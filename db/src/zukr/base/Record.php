<?php


namespace zukr\base;

/**
 * Class Record
 *
 * @package zukr\base
 */
class Record
{
    /**
     * @var \MysqliDb
     */
    private $_db;
    private $_table;


    public function __construct()
    {
        $this->_db = Base::$app->db;
        $this->_table = $this->getTableName();
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

    public function getTableName()
    {
        return \strtolower($this->getNameModel());
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
     * @param $id
     * @return \MysqliDb
     * @throws \Exception
     */
    public function findById($id)
    {

        if (filter_var($id, FILTER_VALIDATE_INT,FILTER_REQUIRE_ARRAY )) {
            return $this->_db
                ->where('id', $id, 'IN')
                ->get($this->_table);
        }elseif (filter_var($id,FILTER_VALIDATE_INT)){
            return
                $this->_db->where('id', $id)
                    ->getOne($this->_table);

        }else{
            throw new \InvalidArgumentException('Array or Int');
        }



    }

}