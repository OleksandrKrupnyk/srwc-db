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
    /**
     * @var string
     */
    protected $__className = Degree::class;
    /**
     * @var array
     */
    private $degrees;

    /**
     * Список повних назв наукових ступенів
     *
     * @return array Список наукових ступенів
     */
    public function getDropDownList(): array
    {
        $degreesList = $this->getDegrees();
        $degrees = [];
        if (!empty($degreesList)) {
            foreach ($degreesList as $id => $d) {
                $degrees [$id] = $d['degreefull'];
            }
        }
        return $degrees;
    }

    /**
     * @return array|mixed
     */
    public function getDegrees()
    {
        if ($this->degrees === null) {
            $statuses = Base::$app->cacheGetOrSet(Degree::class,
                function () {
                    return $this->getDegreesFormDB();
                },
                3600);
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
                ->get(Degree::getTableName());
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}