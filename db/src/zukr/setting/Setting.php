<?php


namespace zukr\setting;

use zukr\base\Record;

/**
 * Class Setting
 *
 * @package      zukr\setting
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Setting extends Record
{

    /**
     * @var string
     */
    public $parametr;
    /**
     * @var string
     */
    public $value;
    /**
     * @var
     */
    public $description;


    /**
     * @{inheritDoc}
     */
    public static function getTableName(): string
    {
        return 'settings';
    }

    /**
     * Повертає назву колонки ключа
     *
     * @return string
     */
    public static function getPrimaryKey(): string
    {
        return 'parametr';
    }

}