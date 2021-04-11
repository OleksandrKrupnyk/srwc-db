<?php


namespace zukr\template;

/**
 * Class Template
 *
 * @property int $id
 * @property string $name
 * @property string $version
 * @property string $content
 * @property string $params
 * @property string $description
 * @property string $page_url
 * @property string $published
 * @property string $update_at
 *
 * @package zukr\template
 * @author Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Template extends \zukr\base\Record
{
    protected const DONT_STRIP_TAGS = [
        'content'
    ];
    /**
     * @var int ІД запису
     */
    public $id;
    /**
     * @var string Унікальне ім'я сторінки
     */
    public $name;
    /**
     * @var string Версія шаблону сторінки
     */
    public $version;
    /**
     * @var string Вміст шаблону
     */
    public $content;
    /**
     * @var string Параметри блоку
     */
    public $params;
    /**
     * @var string Опис сторінки шаблону
     */
    public $description;
    /**
     * @var string Адреси сторінок де використовується
     */
    public $page_url;
    /**
     * @var bool Відмітка про активність блоку
     */
    public $published;
    /**
     * @var string Дата останнього редагування
     */
    public $update_at;


    /**
     * @inheritDoc
     */
    public static function getTableName(): string
    {
        return 'template';
    }
}