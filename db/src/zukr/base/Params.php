<?php


namespace zukr\base;

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
 *
 * @package zukr\base
 */
class Params
{


    private $db;

    public static function tableName()
    {
        return 'settings';
    }

    public function __construct()
    {
        $this->db = Base::$app->db;
        $this->load();
    }


    private $params = [];

    public function __get($name)
    {
        return isset($this->params[$name]) ? $this->params[$name]['value'] : 'not set';
    }

    public function __set($name, $value)
    {
        $keys = array_keys($this->params);
        if (in_array($name, $keys)) {
            $this->params[$name]['value'] = $value;
        }
    }

    public function __isset($name)
    {
        return isset($this->params[$name]);
    }


    public function load()
    {

        $params       = $this->db->get(self::tableName());
        $keys         = array_map(function ($array) {
            return $array['parametr'];
        }, $params);
        $this->params = array_combine($keys, $params);
    }

    public function getParams()
    {
        return $this->params;
    }

}