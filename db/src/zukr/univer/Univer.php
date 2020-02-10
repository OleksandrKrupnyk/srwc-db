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
    /**
     *
     */
    protected const FLUSH_CACHE = true;
    /**
     * @var string Скорочена назва університету
     */
    public $univer;
    /**
     * @var string Повна назва унівверситету
     */
    public $univerfull;
    /**
     * @var string Університет у родовому відмінку
     */
    public $univerrod;
    /**
     * @var int Поштовий індекс
     */
    public $zipcode;
    /**
     * @var string Поштова адреса
     */
    public $adress;
    /**
     * @var string Посада ректора
     */
    public $posada;
    /**
     * @var string Пізвище та ініціали ректора(керівника підрозділу) у родовому відмінку
     */
    public $rector_r;
    /**
     * @var string Сторінка ВНЗ у мережі
     */
    public $http;
    /**
     * @var string Місто, де знаходиться унвіреситет/підрозділ
     */
    public $town;
    /**
     * @var int Ознака запрошення університета до участі
     */
    public $invite;


    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'univers';
    }

}