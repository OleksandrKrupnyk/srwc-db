<?php


namespace zukr\workleader;


use zukr\base\Record;

/**
 * Class WorkLeader
 *
 * @package      zukr\workleader
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkLeader extends Record
{
    /**
     * @var int
     */
    public $id;
    /** @var int */
    public $id_w;
    /** @var int */
    public $id_l;
    /** @var string */
    public $date = 'NOW';

    /**
     * @inheritDoc
     */
    public static function getTableName(): string
    {
        return 'wl';
    }

}