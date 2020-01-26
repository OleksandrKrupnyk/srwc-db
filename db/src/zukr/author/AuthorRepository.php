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
    /**
     * @var string
     */
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

    /**
     * @return array Список авторів для поселелння в гуртожитку
     */
    public function getListAutorsForHostel(): array
    {
        try {
            $r = $this->model::find()->rawQuery("
SELECT a.suname,a.name,a.lname, 
       u.univerrod AS univer, 
       u.id AS id 
FROM `autors` as a 
    LEFT JOIN univers AS u ON u.id=a.id_u 
    LEFT JOIN wa ON a.id=wa.id_a 
    LEFT JOIN works ON wa.id_w = works.id 
WHERE works.invitation = 1 AND u.id <> '1' 
ORDER BY univer,suname");
            return $r ?? [];
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}