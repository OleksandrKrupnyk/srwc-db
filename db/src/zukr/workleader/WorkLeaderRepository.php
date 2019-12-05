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
}