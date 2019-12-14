<?php


namespace zukr\review;


use zukr\base\Record;

class Review extends Record
{
    public $id;
    public $id_w;
    public $actual        = 0;
    public $original      = 0;
    public $methods       = 0;
    public $theoretical   = 0;
    public $practical     = 0;
    public $literature    = 0;
    public $selfcontained = 0;
    public $design        = 0;
    public $publication   = 0;
    public $government    = 0;
    public $tendentious   = 0;
    public $defects       = '';
    public $conclusion;
    public $review1;
    public $date          = 'NOW';


    public static function getTableName(): string
    {
        return 'reviews';
    }

}