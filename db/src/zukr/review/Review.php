<?php


namespace zukr\review;


use zukr\base\Record;

/**
 * Class Review
 *
 * Модель рецензії
 *
 * @package      zukr\review
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Review extends Record
{
    /** @var int ІД запису */
    public $id;
    /** @var int ІД роботи*/
    public $id_w;
    /** @var int int Показник актуальності */
    public $actual = 0;
    /** @var int Новизна та оригінальність ідей */
    public $original = 0;
    /** @var int Використані методи дослідження */
    public $methods = 0;
    /** @var int Теоретичні наукові результати */
    public $theoretical = 0;
    /** @var int Практична направленість результатів<br>(документальне підтвердження впровадження результатів роботи) */
    public $practical = 0;
    /** @var int Рівень використання наукової літератури та інших джерел інформації */
    public $literature = 0;
    /** @var int Ступінь самостійності роботи */
    public $selfcontained = 0;
    /** @var int Якість оформлення */
    public $design = 0;
    /** @var int Наукові публікації */
    public $publication = 0;
    /** @var int Відповідність роботи Державній програмі<br>пріоритетних напрямків інноваційної діяльності */
    public $government = 0;
    /** @var int Відповідність роботи сучасним світовим тенденціям розвитку
     * електроенергетики, електротехніки та
     * електромеханіки
     */
    public $tendentious = 0;
    /** @var string  Зауважання рецензента */
    public $defects = '';
    /** @var int Рішення */
    public $conclusion;
    /** @var int ІД запису рецензента */
    public $review1;
    /** @var string Остання дата редагування */
    public $date = 'NOW';

    /**
     * @return string Повертає назву таблиці
     */
    public static function getTableName(): string
    {
        return 'reviews';
    }

}