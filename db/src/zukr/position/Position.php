<?php


namespace zukr\position;


use zukr\base\Record;

/**
 * Class Position
 *
 * Посада
 *
 * @package      zukr\position
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Position extends Record
{
    /** @var int ІД запису */
    public $id;
    /** @var string Посада */
    public $position;


    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'positions';
    }

}