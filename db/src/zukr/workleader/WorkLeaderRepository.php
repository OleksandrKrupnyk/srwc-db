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
    /**
     * @var string
     */
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
            return [];
        }

    }


    /**
     * Дані по усіх керівниках роботи за переданим ІД роботи
     *
     * @param int $workId ІД запису роботи
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
            return [];
        }
    }

    /**
     * Спиок ІД керівників роботи
     *
     * @param int $workId ІД роботи
     * @return array|\MysqliDb
     */
    public function getLeadersIdsByWorkId(int $workId)
    {
        try {
            return $this->model::find()
                ->where('id_w', $workId)
                ->get($this->model::getTableName(), null, 'id_l');
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

    /**
     * @param int $id ІД запису керівника роботи
     * @return array|\MysqliDb
     */
    public function getByLeaderId(int $id)
    {
        $table = $this->model::getTableName();
        try {
            return $this->model::find()
                ->where('id_l', $id)
                ->get($table);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}