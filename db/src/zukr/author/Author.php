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
    public static function getTableName()
    {
        return 'autors';
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->hash = md5($this->suname . $this->name . $this->lname);
        $this->arrival = $this->arrival !== '0' ? '1' : '0';
        $this->bprint = $this->bprint !== '0' ? '1' : '0';
        $this->place = ($this->arrival === '1') ? $this->place : 'D';
        return parent::beforeSave();
    }


}