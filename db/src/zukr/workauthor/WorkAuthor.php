<?php


namespace zukr\workauthor;


use zukr\base\Record;

/**
 * Class WorkAuthor
 *
 * @package      zukr\workauthor
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkAuthor extends Record
{

    protected const FLUSH_CACHE = true;
    /**
     * @var int ІД запису
     */
    public $id;
    /**
     * @var  int ІД запису роботи
     */
    public $id_w;
    /**
     * @var int ІД запису автора
     */
    public $id_a;
    /**
     * @var string
     */
    public $date = 'NOW';

    /**
     * @inheritDoc
     */
    public static function getTableName(): string
    {
        return 'wa';
    }

}