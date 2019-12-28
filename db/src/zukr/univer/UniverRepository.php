<?php


namespace zukr\univer;

use zukr\base\AbstractRepository;
use zukr\base\Base;

/**
 * Class UniverRepository
 *
 * Репозиторій доступу до записів університетів
 *
 * @package      zukr\univer
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UniverRepository extends AbstractRepository
{
    /**
     * @var string
     */
    protected $__className = Univer::class;
    /**
     * @return array|\MysqliDb
     */
    public function getAllUniversAsArrayFromDB(): array
    {
        try {
            return Univer::find()
                ->map('id')
                ->orderBy('univerfull','ASC')
                ->get(Univer::getTableName());

        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}