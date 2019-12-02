<?php


namespace zukr\work;


use zukr\base\Record;

class Work extends Record
{

    public $id;
    public $id_u;
    public $title;
    public $motto;
    public $id_sec;
    public $public;
    public $introduction;
    public $invitation;
    public $arrival;
    public $tesis;
    public $dead;
    public $comments;
    /** @var int */
    public $balls;


    public static function getTableName(): string
    {
        return 'works';
    }


}