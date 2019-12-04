<?php


namespace zukr\workauthor;


use zukr\base\Record;

/**
 * Class WorkAuthor
 *
 * @package      zukr\workauthor
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkAuthor extends Record
{

    public $id;
    public $id_w;
    public $id_a;
    public $date='NOW';

    /**
     * @inheritDoc
     */
    public static function getTableName():string
    {
        return 'wa';
    }

}