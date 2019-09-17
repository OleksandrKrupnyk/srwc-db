<?php


namespace zukr\univer;


use zukr\base\Record;

class Univer extends Record
{

    public $univer;
    public $univerfull;
    public $univerrod;
    public $zipcode;
    public $adress;
    public $posada;
    public $rector_r;
    public $http;
    public $town;
    public $invite;


    public static function getTableName()
    {
        return 'univers';
    }


}