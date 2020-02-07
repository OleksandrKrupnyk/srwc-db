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
     */
    public function getDropDownList()
    {
        if ($this->degrees === null) {
            $statuses = Base::$app->cacheGetOrSet(static::class,
                function () {
                    return $this->getDegreesFormDB();
                },
                60);
            $this->degrees = $statuses;
        }
        return $this->degrees;
    }

    /**
     * @return array|\MysqliDb
     */
    private function getDegreesFormDB(): array
    {
        try {
            return Degree::find()
                ->map('id')
                ->get(Degree::getTableName(), null, ['id', 'degreefull']);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}