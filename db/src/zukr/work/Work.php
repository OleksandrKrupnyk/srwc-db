<?php


namespace zukr\work;


use zukr\base\Record;

/**
 * Class Work
 *
 * @package      zukr\work
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Work extends Record
{
    /**
     *
     */
    public const PERSON_EXIST = 888;
    /**
     * @var int ІД запису
     */
    public $id;
    /**
     * @var int ІД запису університету
     */
    public $id_u;
    /**
     * @var string Назва роботи
     */
    public $title;
    /**
     * @var string Дивіз / шифр роботи
     */
    public $motto;
    /**
     * @var int ІД запису секції
     */
    public $id_sec;
    /**
     * @var string Відомості про публікацію
     */
    public $public;
    /**
     * @var string Відомості про впровадження
     */
    public $introduction;
    /**
     * @var string Відмітка про запрошення роботи
     */
    public $invitation;
    /**
     * @var string Відмітка про прибуття роботи
     */
    public $arrival;
    /**
     * @var string Відмітка про наявність тезисів
     */
    public $tesis;
    /**
     * @var int Сліжбова відмітка
     */
    public $dead = 0;
    /**
     * @var string Службові коментарі до роботи
     */
    public $comments = '';
    /**
     * @var int Сімарна кількість балів
     */
    public $balls = 0;

    /**
     * @return string Назва таблиці в базі даних
     */
    public static function getTableName(): string
    {
        return 'works';
    }

    /**
     * Виконується перед збреженням
     *
     * @return bool Результат виконання
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