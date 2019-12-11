<?php


namespace zukr\work;


use zukr\base\AbstractRepository;
use zukr\base\Base;
use zukr\univer\Univer;

/**
 * Class WorkRepository
 *
 * @package      zukr\work
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkRepository extends AbstractRepository
{
    /** @var string */
    public $__className = Work::class;

    /**
     * @return \MysqliDb
     */
    protected function findAllWorks()
    {
        return Work::find()
            ->map('id');
    }

    /**
     * @return array|\MysqliDb
     */
    public function getAllWorksAsArray()
    {
        try {

            $univerTable = Univer::getTableName();
            return $this->findAllWorks()
                ->join(Univer::getTableName(), Work::getTableName() . ".id_u={$univerTable}.id", 'LEFT')
                ->orderBy('univerfull', 'ASC')
                ->get(Work::getTableName(), null, Work::getTableName() . '.*, univerfull');
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }


}