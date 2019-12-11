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
    /** @var array */
    public const ROOMS = [
        '7-43', '7-53', '7-54'
    ];
    /** @var int */
    public $id;
    public $section;
    public $room = '7-53';

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'sections';
    }

}