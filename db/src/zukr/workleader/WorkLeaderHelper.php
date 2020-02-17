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
    /**
     * @var WorkLeaderHelper
     */
    private static $obj;

    /**
     * @var WorkLeaderRepository
     */
    private $workLeaderRepository;

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
        $leaders = $this->getWorkLeaderRepository()->getAllLeadersOfWorkByWorkId($workId);
        return \array_map(static function ($v) {
            return $v['id'];
        }, $leaders);
    }

    /**
     * @return WorkLeaderRepository
     */
    public function getWorkLeaderRepository(): WorkLeaderRepository
    {
        if ($this->workLeaderRepository === null) {
            $this->workLeaderRepository = new WorkLeaderRepository();
        }
        return $this->workLeaderRepository;
    }

}