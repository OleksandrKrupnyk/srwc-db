<?php


namespace zukr\scanfiles;


use zukr\base\Record;

/**
 * Class ScanFiles
 *
 * @property int $id
 * @property int $id_u
 * @property string $filename
 * @property string $file
 * @property string $md5sum
 * @property string $date
 *
 * @package zukr\scanfiles
 * @author Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ScanFiles extends Record
{

    /**
     * @var int
     */
    public $id;
    /**
     * @var int ІД університету
     */
    public $id_u;
    /**
     * @var string Назва файлу
     */
    public $filename;
    /**
     * @var string Шлях до файлу
     */
    public $file;
    /**
     * @var string
     */
    public $md5sum;
    /**
     * @var string Дата завантаження
     */
    public $date = 'NOW';

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'scanfiles';
    }
}