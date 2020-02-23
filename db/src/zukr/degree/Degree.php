<?php


namespace zukr\degree;


use zukr\base\Record;

/**
 * Class Degree
 *
 * Запис про науковий ступінь
 *
 * @package      zukr\degree
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Degree extends Record
{
    /**
     *
     */
    public const NO_DEGREE_TITLE = '-немає-';
    /**
     *
     */
    public const NO_DEGREE_ID = 1;
    /**
     *
     */
    public const FLUSH_CACHE = true;
    /**
     * @var int ІД запису
     */
    public $id;
    /**
     * @var string  Скорочена назва наукового ступуню
     */
    public $degree;
    /**
     * @var string Науковий ступінь повністю
     */
    public $degreefull;
    /**
     * @var string Науковий ступінь в давальному відмінку
     */
    public $degreegive;

    /**
     * @{inheritDoc}
     */
    public static function getTableName(): string
    {
        return 'degrees';
    }


}