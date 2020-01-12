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
     *
     */
    public const PLACES = [
        'I',
        'II',
        'III',
        'D'
    ];
    /**
     * @var int
     */
    public $id;
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
     * @var int Курс навчання
     */
    public $curse = 0;
    /**
     * @var string Пошта
     */
    public $email = '';
    /**
     * @var string Призове місце
     */
    public $place   = 'D';
    public $active  = 0;
    public $arrival = 0;
    public $phone   = '';
    public $date    = 'NOW';
    /**
     * @var string
     */
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