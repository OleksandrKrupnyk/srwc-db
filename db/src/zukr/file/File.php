<?php


namespace zukr\file;


use zukr\base\Record;

/**
 * Class File
 *
 * @package      zukr\file
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class File extends Record
{

    protected const FLUSH_CACHE = true;
    /**
     * @var int Файли роботи
     */
    public const TYPE_WORK = 0;
    /**
     * @var int файли тезисів
     */
    public const TYPE_TESIS = 1;
    /**
     * @var int Файли презентацій
     */
    public const TYPE_OFFICE_PRESENTATION = 2;


    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $id_w;
    /**
     * @var string
     */
    public $file;
    /**
     * @var int
     */
    public $typeoffile = 0;
    /**
     * @var string
     */
    public $date = 'NOW';

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'files';
    }

    /**
     * @return array
     */
    public static function getTypes(): array
    {
        return [self::TYPE_WORK, self::TYPE_TESIS, self::TYPE_OFFICE_PRESENTATION];
    }

}