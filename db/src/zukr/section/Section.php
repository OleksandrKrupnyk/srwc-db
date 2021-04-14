<?php


namespace zukr\section;


use zukr\base\Record;

/**
 * Class Section
 *
 * @package      zukr\section
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Section extends Record
{
    /**
     *
     */
    protected const FLUSH_CACHE = true;
    /**
     * @var array
     */
    public const ROOMS = [
        '7-43', '7-53', '7-54'
    ];
    /**
     * @var int
     */
    public $id;
    /**
     * @var string Назва секції
     */
    public $section;
    /**
     * @var string Аудиторія за замовчуванням
     */
    public $room = '7-53';
    /**
     * @var string Посилання
     */
    public $link = '#';

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'sections';
    }

}