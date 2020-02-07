<?php


namespace zukr\position;

use zukr\base\AbstractRepository;
use zukr\base\Base;

/**
 * Class PositionRepository
 *
 * @package      zukr\position
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class PositionRepository extends AbstractRepository
{
    /**
     * @var array
     */
    private $positions;

    /** @var string */
    protected $__className = Position::class;

    /**
     * @return array|\MysqliDb
     */
    public function getDropDownList()
    {
        if ($this->positions === null) {
            $positions = Base::$app->cacheGetOrSet(
                static::class,
                function () {
                    return $this->getPositionsFormDB();
                },
                60);
            $this->positions = $positions;
        }
        return $this->positions;
    }

    /**
     * @return array|\MysqliDb
     */
    private function getPositionsFormDB()
    {
        try {
            return Position::find()
                ->map('id')
                ->get(Position::getTableName(), null, ['id', "position"]);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}