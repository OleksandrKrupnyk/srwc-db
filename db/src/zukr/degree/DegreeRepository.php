<?php


namespace zukr\degree;


use zukr\base\AbstractRepository;
use zukr\base\Base;

/**
 * Class DegreeRepository
 *
 * @package      zukr\degree
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class DegreeRepository extends AbstractRepository
{
    /** @var string */
    protected $__className = Degree::class;
    /**
     * @var array
     */
    private $degrees;

    /**
     * @return array|\MysqliDb
     * @throws \Exception
     */
    public function getDropDownList()
    {
        if ($this->degrees === null) {
            $statuses = Base::$app->cacheGetOrSet(get_called_class(), $this->getDegreesFormDB(), 3600);
            $this->degrees = $statuses;
        }
        return $this->degrees;
    }


    private function getDegreesFormDB()
    {
        return Degree::find()
            ->map('id')
            ->get(Degree::getTableName(), null, ['id', 'degreefull']);
    }
}