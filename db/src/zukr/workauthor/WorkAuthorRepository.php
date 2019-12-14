<?php


namespace zukr\workauthor;


use zukr\author\Author;
use zukr\base\AbstractRepository;
use zukr\base\Base;

/**
 * Class WorkAuthorRepository
 *
 * @package      zukr\workauthor
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkAuthorRepository extends AbstractRepository
{
    /** @var string */
    public $__className = WorkAuthor::class;
    /**
     * @var WorkAuthor
     */
    public $model;

    /**
     * @return array|\MysqliDb
     */
    public function getAllAuthorsOfWorks()
    {
        $table = $this->model::getTableName();
        $joinTable = Author::getTableName();
        try {
            return $this->model::find()
                ->join($joinTable, $table . '.id_a=' . $joinTable . '.id')
                ->get($table, null, $table . '.date,id_w, ' . $joinTable . '.*');
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

    /**
     * @param int $workId
     * @return array|\MysqliDb
     */
    public function getAllAuthorsOfWorkByWorkId(int $workId)
    {
        $table = $this->model::getTableName();
        $joinTable = Author::getTableName();
        try {
            return $this->model::find()
                ->join($joinTable, $table . '.id_a=' . $joinTable . '.id')
                ->where('id_w', $workId)
                ->get($table, null, $table . '.date,id_w, ' . $joinTable . '.*');
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

    /**
     * @param int $id
     * @return array|\MysqliDb
     */
    public function getByAuthorId(int $id)
    {
        $table = $this->model::getTableName();
        try {
            return $this->model::find()
                ->where('id_a', $id)
                ->get($table);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}