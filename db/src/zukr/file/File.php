<?php


namespace zukr\file;


use zukr\base\Record;

/**
 * Class File
 *
 * @property int $id_w
 * @property string $file
 * @property int $typeoffile
 * @property string $date
 * @property string $mime_type
 * @property string $guid
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
     * @int int Файл
     */
    public const TYPE_INFORMATION = 3;


    /**
     * @var int
     */
    public $id;
    /**
     * @var int  ІД запис роботи
     */
    public $id_w;
    /**
     * @var string
     */
    public $file;
    /**
     * @var int Тип файлу
     */
    public $typeoffile = 0;
    /**
     * @var string
     */
    public $date = 'NOW';
    /**
     * @var string (127)
     */
    public $mime_type;
    /**
     * @var string (36)
     */
    public $guid;

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