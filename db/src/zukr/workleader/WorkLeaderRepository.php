<?php


namespace zukr\workleader;


use zukr\base\AbstractRepository;
use zukr\base\Base;
use zukr\leader\Leader;

/**
 * Class WorkLeaderRepository
 *
 * @package      zukr\workauthor
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkLeaderRepository extends AbstractRepository
{
    /** @var string */
    public $__className = WorkLeader::class;
    /**
     * @var WorkLeader
     */
    public $model;

    /**
     * @return array|\MysqliDb
     */
    public function getAllLeadersOfWorks()
    {
        $table = $this->model::getTableName();
        $joinTable = Leader::getTableName();
        try {
            return $this->model::find()
                ->join($joinTable, $table . '.id_l=' . $joinTable . '.id')
                ->get($table, null, $table . '.date,id_w, ' . $joinTable . '.*');
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
        }

    }


    /**
     * @return array|\MysqliDb
     */
    public function getAllLeadersOfWorkByWorkId(int $workId)
    {
        $table = $this->model::getTableName();
        $joinTable = Leader::getTableName();
        try {
            return $this->model::find()
                ->join($joinTable, $table . '.id_l=' . $joinTable . '.id')
                ->where('id_w', $workId)
                ->get($table, null, $table . '.date,id_w, ' . $joinTable . '.*');
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
        }
    }


    public function getCountAuthorsByWorkId(int $workId)
    {
        try {
            return $this->model::find()
                ->where('id_w', $workId)
                ->get($this->model::getTableName(), null, 'count(*) as count');
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
        }
    }
}