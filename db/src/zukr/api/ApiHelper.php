<?php


namespace zukr\api;


use zukr\base\Base;
use zukr\base\helpers\StringHelper;

/**
 * Class ApiHelper
 *
 * @package      zukr\api
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ApiHelper
{
    const PATH = 'zukr\\api\\actions\\';

    /** @var ApiHelper */
    private static $obj;

    /**
     * WorkHelper constructor.
     */
    private function __construct()
    {
        Base::init();
    }

    /**
     * @return ApiHelper
     */
    public static function getInstance(): ApiHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;
    }

    /**
     * @param string $action
     * @return object
     */
    public function getActionByName(string $action)
    {
        $action = StringHelper::id2camel($action);
        $className = $this->getClassName($action);
        if (\class_exists($className)) {
            return new $className();
        }

        Base::$log->critical(__METHOD__ . ' Not found class ' . $className);
        return new class() implements \zukr\api\actions\ApiActionsInterface {
            public function execute(){}
            public function init(array $params = []){}
        } ;
    }

    /**
     * @param string $action
     * @return string
     */
    protected function getClassName(string $action): string
    {
        return self::PATH . $action . 'Action';
    }


}