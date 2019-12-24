<?php


namespace zukr\work;


use zukr\base\Record;

class Work extends Record
{

    public $id;
    public $id_u;
    public $title;
    public $motto;
    public $id_sec;
    public $public;
    public $introduction;
    public $invitation;
    public $arrival;
    public $tesis;
    public $dead     = 0;
    public $comments = '';
    /** @var int */
    public $balls = 0;

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'works';
    }

    /**
     * @return bool
     */
    public function beforeSave(): bool
    {
        $this->arrival = (int)$this->arrival !== self::KEY_OFF ? self::KEY_ON : self::KEY_OFF;
        $this->invitation = (int)$this->invitation !== self::KEY_OFF ? self::KEY_ON : self::KEY_OFF;
        $this->dead = (int)$this->dead !== self::KEY_OFF ? self::KEY_ON : self::KEY_OFF;
        $this->tesis = (int)$this->tesis !== self::KEY_OFF ? self::KEY_ON : self::KEY_OFF;
        return parent::beforeSave();
    }

}