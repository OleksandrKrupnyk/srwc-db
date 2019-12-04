<?php


namespace zukr\base;
/**
 * Class Base
 *
 * @package      zukr\base
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Base
{

    /**
     * @var App
     */
    public static $app;
    /**
     * @var Params
     */
    public static $param;

    /** @var Session */
    public static $session;
    /** @var Logger */
    public static $log;


    /**
     *
     */
    public static function init()
    {

        self::$app = App::getInstance();
        self::$param = Params::getInstance();
        self::$session = Session::getInstance();
        self::$log = Logger::getInstance();
    }

}