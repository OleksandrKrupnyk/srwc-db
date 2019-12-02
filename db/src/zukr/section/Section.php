<?php


namespace zukr\section;


use zukr\base\Record;

class Section extends Record
{
    public $id;
    public $section;
    public $room;

    public static function getTableName(): string
    {
        return 'sections';
    }

}