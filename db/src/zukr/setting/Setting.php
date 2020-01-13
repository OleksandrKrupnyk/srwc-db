<?php


namespace zukr\setting;

use zukr\base\Record;

/**
 * Class Setting
 *
 * Модель запису налаштування системи
 *
 * @package      zukr\setting
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Setting extends Record
{

    const BOOL   = 'bool';
    const STRING = 'string';
    const INT    = 'int';
    const TYPES  = [
        self::BOOL,
        self::STRING,
        self::INT
    ];
    /**
     * @var string Ключ параметру
     */
    public $parametr;
    /**
     * @var string Значення
     */
    public $value;
    /**
     * @var string Опис поля
     */
    public $description;
    /**
     * @var string
     */
    public $type;

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

    /**
     * @return bool
     */
    public function beforeSave()
    {
        if (!\in_array($this->type, self::TYPES)) {
            return false;
        }
        return parent::beforeSave();
    }

}