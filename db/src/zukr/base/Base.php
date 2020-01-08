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

    public const KEY_ON  = 1;
    public const KEY_OFF = 0;
    private static $isInit = false;

    const OBJ_SINGLE = [
        'app' => App::class,
        'logs' => Logger::class,
        'param' => Params::class,
        'session' => Session::class,
        'user' => LoginUser::class,
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
     * @var LoginUser
     */
    public static $user;


    /**
     *
     */
    public static function init()
    {
        if (!self::$isInit) {
            self::$session = Session::getInstance();
            self::$app = App::getInstance();
            self::$param = Params::getInstance();
            self::$log = Logger::getInstance();
            self::$user = LoginUser::getInstance();
            self::$isInit = true;
        }

    }

    /**
     *
     */
    public static function setSNRCRF(): void
    {
        if (self::$session !== null && self::$app !== null) {
            $_SNRCRF = \md5('SNRCRF' . time());
            self::$session->set('_SNRCRF', $_SNRCRF);
            self::$app->_snrcrf = $_SNRCRF;
        }
    }

    /**
     * @return string
     */
    public static function getSNRCRF(): string
    {
        if (self::$session !== null && self::$app !== null) {
            $_SNRCRF = self::$session->get('_SNRCRF', null);
            if ($_SNRCRF !== null) {
                self::$app->_snrcrf = $_SNRCRF;
                return $_SNRCRF;
            }
            return null;
        }
        return null;
    }

}