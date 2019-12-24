<?php

namespace zukr\workleader;


/**
 * Class WorkLeaderHelper
 *
 * @package      zukr\workleader
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkLeaderHelper
{
    /** @var WorkLeaderHelper */
    private static $obj;

    /**
     * WorkHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return WorkLeaderHelper
     */
    public static function getInstance(): WorkLeaderHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }

    /**
     * @param int $workId
     * @return array|\MysqliDb
     */
    public function getIdsLeadersOfWorkByWorkId(int $workId)
    {
        $leaders = (new WorkLeaderRepository())->getAllLeadersOfWorkByWorkId($workId);
        return array_map(static function ($v) {
            return $v['id'];
        }, $leaders);
    }

}