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

    /**
     * @param int $univerId ІД запису університету
     * @return array Список авторів робіт
     */
    public function getAllByUniverId(int $univerId): array
    {
        try {
            $r = $this->model::find()
                ->where('id_u', $univerId)
                ->get($this->model::getTableName(), null, 'id,suname,lname,name');
            return $r ?? [];
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

}