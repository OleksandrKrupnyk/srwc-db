<?php


namespace zukr\base;
/**
 * Class Base
 *
 * @property $name
 * @package      zukr\base
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Base
{


    private static $isInit = false;

    const OBJ_SINGLE = [
        'app' => App::class,
        'logs' => Logger::class,
        'param' => Params::class,
        'session' => Session::class,
    ];
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
        if (!self::$isInit) {
            self::$app = App::getInstance();
            self::$param = Params::getInstance();
            self::$session = Session::getInstance();
            self::$log = Logger::getInstance();
            self::$isInit = true;
        }
    }

}