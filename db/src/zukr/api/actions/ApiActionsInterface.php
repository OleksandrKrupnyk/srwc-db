<?php


namespace zukr\api\actions;

/**
 * Interface ApiActionsInterface
 *
 * @package zukr\api\actions
 */
interface ApiActionsInterface
{

    /** Виполняет основное назаначение задания */
    public function execute();

    /**  */
    public function init(array $params = []);

}