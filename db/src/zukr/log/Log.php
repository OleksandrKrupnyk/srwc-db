<?php


namespace zukr\log;


use zukr\base\Record;

/**
 * Class Log
 *
 * @package      zukr\log
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Log extends Record
{
    /**
     * @var Log
     */
    private static $obj;
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $tz_id;
    public $date = 'NOW';
    /** @var string */
    public $action;
    /** @var string */
    public $table;
    /**
     * @var int
     */
    public $action_id;
    /**
     * @var
     */
    public $result;
    /**
     * @var
     */
    public $tz_ip;

    /**
     * @return Log
     */
    public static function getInstance()
    {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return static::$obj;
    }


    /**
     * @param null $action
     * @param null $table
     * @param null $action_id
     */
    public function logAction($action = null, $table = null, $action_id = null)
    {
        $this->action = $action ?? $_POST['action'];
        $this->table = isset($table) ? $table : 'Unknown';
        $this->action_id = $action_id ?? '0';

        $this->save();
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->tz_ip = $_SERVER['REMOTE_ADDR'];
        $this->tz_id = $_SESSION['id'] ?? '777';
        return parent::beforeSave();
    }
}