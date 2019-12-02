<?php


namespace zukr\workauthor;


use zukr\author\Author;
use zukr\base\AbstractRepository;

class WorkAuthorRepository extends AbstractRepository
{

    public $__className = WorkAuthor::class;
    /**
     * @var WorkAuthor
     */
    public $model;

    public function getAllAuthorsOfWorks()
    {
        $table = $this->model::getTableName();
        $joinTable = Author::getTableName();
        return $this->model::find()
            ->join($joinTable, $table . '.id_a=' . $joinTable . '.id')
            ->get($table, null, $table . '.date,id_w, ' . $joinTable . '.*');


    }
}