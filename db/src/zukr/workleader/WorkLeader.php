<?php


namespace zukr\workleader;


use zukr\base\Record;

/**
 * Class WorkLeader
 *
 * Модель відношення робота керівник
 *
 * @package      zukr\workleader
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkLeader extends Record
{
    /**
     *
     */
    protected const FLUSH_CACHE = true;
    /**
     * @var int ІД запису
     */
    public $id;
    /**
     * @var int ІД запису роботи
     */
    public $id_w;
    /**
     * @var int ІД запису керівника
     */
    public $id_l;
    /**
     * @var string
     */
    public $date = 'NOW';

    /**
     * @inheritDoc
     */
    public static function getTableName(): string
    {
        return 'wl';
    }

}