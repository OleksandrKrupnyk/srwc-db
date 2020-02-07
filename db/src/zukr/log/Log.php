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
    protected const NOTIFICATION_ACTIONS = false;
    /**
     * @var Log
     */
    private static $obj;
    /**
     * @var int ІД запису
     */
    public $id;
    /**
     * @var int ІД запису користувача автора дії
     */
    public $tz_id;
    /**
     * @var string Час за замовчуванням
     */
    public $date = 'NOW';
    /**
     * @var string Назва дії
     */
    public $action;
    /**
     * @var string Назва таблиці
     */
    public $table;
    /**
     * @var int ІД запису
     */
    public $action_id;
    /**
     * @var
     */
    public $result;
    /**
     * @var string Інтернет адреса користувача
     */
    public $tz_ip;

    /**
     * @return Log
     */
    public static function getInstance(): Log
    {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return static::$obj;
    }

    /**
     * Повертаэ назву таблиці
     *
     * @return string  Назва таблиці
     */
    public static function getTableName(): string
    {
        return 'log';
    }

    /**
     * @param null|string $action Назва дії
     * @param null|string $table  Назва таблиці
     * @param null        $action_id
     */
    public function logAction(string $action = null, $table = null, $action_id = null): void
    {
        if ($action === null) {
            if (isset($_POST['action'])) {
                $this->action = (string)$_POST['action'];

            } elseif ($_GET['action']) {
                $this->action = (string)$_GET['action'];
            } else {
                $this->action = 'Unknown';
            }
        } else {
            $this->action = $action;
        }
        $this->table = $table ?? 'Unknown';
        $this->action_id = $action_id ?? '0';

        $this->save();
    }


    /**
     * @return bool
     */
    public function beforeSave(): bool
    {
        $this->tz_ip = $_SERVER['REMOTE_ADDR'];
        $this->tz_id = $_SESSION['id'] ?? '777';
        return parent::beforeSave();
    }
}