<?php

namespace zukr\workleader;


use zukr\base\RecordHelper;

/**
 * Class WorkLeaderHelper
 *
 * @package      zukr\workleader
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkLeaderHelper extends RecordHelper
{
    /** @var WorkLeaderHelper */
    private static $obj;

    /**
     * @return WorkLeaderHelper
     */
    public static function getInstance(): WorkLeaderHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
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