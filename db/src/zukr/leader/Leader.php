<?php


namespace zukr\leader;

use zukr\base\Record;

class Leader extends Record
{

    public $id;
    public $id_tzmember;
    public $id_u;
    public $suname;
    public $name;
    public $lname = '';

    public $id_pos;
    public $id_sat;
    public $id_deg;
    public $invitation;
    public $arrival;
    public $review       = 0;
    public $phone        = '';
    public $email        = '';
    public $date         = 'NOW';
    public $page         = '';
    public $hash;
    public $email_recive = 0;
    public $email_date   = '2013-11-25 09:00:00';

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'leaders';
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->hash = \md5($this->suname . $this->name . $this->lname);
        $this->arrival = (int)$this->arrival !== self::KEY_OFF ? self::KEY_ON : self::KEY_OFF;
        $this->review = (int)$this->review !== self::KEY_OFF ? self::KEY_ON : self::KEY_OFF;
        $this->invitation = (int)$this->invitation !== self::KEY_OFF ? self::KEY_ON : self::KEY_OFF;
        return parent::beforeSave();
    }

}