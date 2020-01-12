<?php


namespace zukr\leader;

use zukr\base\Record;

/**
 * Class Leader
 *
 * @package      zukr\leader
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Leader extends Record
{

    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $id_tzmember;
    /**
     * @var int
     */
    public $id_u;
    /**
     * @var string
     */
    public $suname;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $lname = '';
    /**
     * @var int
     */
    public $id_pos;
    /**
     * @var int
     */
    public $id_sat;
    /**
     * @var int
     */
    public $id_deg;
    /**
     * @var int
     */
    public $invitation;
    /**
     * @var int
     */
    public $arrival = 0;
    /**
     * @var int
     */
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