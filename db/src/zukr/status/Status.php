<?php


namespace zukr\status;


use zukr\base\Record;

/**
 * Class Status
 *
 * Вчений статус
 *
 * @package      zukr\status
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Status extends Record
{
    /**
     *
     */
    public const NO_STATUS_TITLE = '-немає-';
    /**
     *
     */
    public const NO_STATUS_ID = 1;
    /**
     *
     */
    protected const FLUSH_CACHE = true;
    /**
     * @var int ІД запису
     */
    public $id;
    /**
     * @var string Скорочено вчений статус
     */
    public $status;
    /**
     * @var string Вчений статус повністю
     */
    public $statusfull;
    /**
     * @var string Вчений статус в давальному відмінку
     */
    public $statusgive;


    /**
     * @{inheritDoc}
     */
    public static function getTableName(): string
    {
        return 'statuses';
    }


}