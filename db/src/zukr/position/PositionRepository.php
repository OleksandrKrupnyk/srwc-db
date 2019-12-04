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
     * @throws \Exception
     */
    public function getDropDownList()
    {
        if ($this->positions === null) {
            $positions = Base::$app->cacheGetOrSet(get_called_class(), $this->getPositionsFormDB(), 3600);
            $this->positions = $positions;
        }
        return $this->positions;
    }


    private function getPositionsFormDB()
    {

        return Position::find()
            ->map('id')
            ->get(Position::getTableName(), null, ['id', "position"]);
    }
}