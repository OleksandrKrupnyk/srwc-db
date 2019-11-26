<?php


namespace zukr\workauthor;


use zukr\base\Record;

class WorkAuthor extends Record
{

    public $id;
    public $id_w;
    public $id_a;
    public $date='NOW';

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'wa';
    }

}