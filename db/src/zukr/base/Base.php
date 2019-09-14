<?php


namespace zukr\base;

class Base
{

    /**
     * @var App
     */
    public static $app;

    public static $param;

    public static function init(){

        self::$app = new App();
        self::$param = new Params();
    }

}