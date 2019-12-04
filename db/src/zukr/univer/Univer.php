<?php


namespace zukr\univer;


use zukr\base\Record;

/**
 * Class Univer
 *
 * @package      zukr\univer
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
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


    /**
     * @return string
     */
    public static function getTableName():string
    {
        return 'univers';
    }


}