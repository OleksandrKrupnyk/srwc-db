<?php

use zukr\api\actions\ApiActionsInterface;
use zukr\api\ApiHelper;
use zukr\base\Base;

header("Content-Type: text/html; charset=utf-8");
require '../vendor/autoload.php';

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRIPPED);
try {
    $apih = ApiHelper::getInstance();
    /** ApiActionsInterface $classObj */
    $classObj = $apih->getActionByName($action);
    if ($classObj instanceof ApiActionsInterface) {
        $classObj->init();
        echo $classObj->execute();
    }
} catch (\Exception $e) {
    if (isset(Base::$log) && Base::$log !== null) {
        Base::$log->error($e->getMessage());
    }
}