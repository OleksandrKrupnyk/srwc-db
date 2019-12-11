<?php


namespace zukr\author;

use zukr\base\AbstractRepository;
use zukr\base\Base;

/**
 * Class AuthorRepository
 *
 * @package      zukr\author
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class AuthorRepository extends AbstractRepository
{
    /** @var string */
    public $__className = Author::class;

    /**
     * Усі дані по усіх авторах
     *
     * @return array|\MysqliDb
     */
    public function getAllAuthors(): array
    {
        $table = $this->model::getTableName();
        try {
            return $this->model::find()
                ->get($table);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }


    }


}