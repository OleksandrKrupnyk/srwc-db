<?php


namespace zukr\author;


use zukr\base\Record;

class Author extends Record
{
    public static function getTableName()
    {
        return 'autors';
    }

    public function beforeSave()
    {
        $this->hash = md5($this->suname . $this->name . $this->lname);
        return parent::beforeSave();
    }

    public $id;
    public $id_u;
    public $suname;
    public $name;
    public $lname='';
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


}