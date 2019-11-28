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

    /**
     *
     */
    public static function init(){

        self::$app = App::getInstance();
        self::$param = Params::getInstance();
    }

}