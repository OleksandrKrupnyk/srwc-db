<?php


namespace zukr\author;


use zukr\base\Record;

/**
 * Class Author
 *
 * @package      zukr\author
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Author extends Record
{
    /**
     * @var int
     */
    public $id;
    public $id_u;
    public $suname;
    public $name;
    public $lname        = '';
    public $curse        = 0;
    public $email        = '';
    public $place        = 'D';
    public $active       = 0;
    public $arrival      = 0;
    public $phone        = '';
    public $date         = 'NOW';
    public $hash;
    public $email_recive = 0;
    public $email_date   = '2013-11-25 09:00:00';
    public $bprint       = 0;

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'autors';
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->hash = \md5($this->suname . $this->name . $this->lname);
        $this->arrival = (int)$this->arrival !== self::KEY_OFF ? self::KEY_ON : self::KEY_OFF;
        $this->bprint = (int)$this->bprint !== self::KEY_OFF ? self::KEY_ON : self::KEY_OFF;
        $this->place = ($this->arrival === self::KEY_ON) ? $this->place : 'D';
        return parent::beforeSave();
    }


}