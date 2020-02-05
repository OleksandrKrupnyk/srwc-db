<?php


namespace zukr\base;
use Monolog\Logger as MonologLogger;

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

    /**
     * @var App Додаток
     */
    public static $app;
    /**
     * @var Params Налаштування
     */
    public static $param;

    /**
     * @var Session
     */
    public static $session;
    /**
     * @var MonologLogger
     */
    public static $log;
    /**
     * @var LoginUser
     */
    public static $user;
    /**
     * @var Dir
     */
    public static $dir;

    /**
     *
     */
    public static function init()
    {
        if (!self::$isInit) {
            $loggerBuilder = LoggerBuilder::getInstance();
            self::$session = Session::getInstance();
            self::$app = App::getInstance();
            $logFile = \realpath(\getenv('LOG_FILE') ?? '/var/log/elm.log');
            self::$param = Params::getInstance();
            self::$log = $loggerBuilder->getLogger($logFile);
            self::$user = LoginUser::getInstance();
            self::$dir = Dir::getInstance();
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