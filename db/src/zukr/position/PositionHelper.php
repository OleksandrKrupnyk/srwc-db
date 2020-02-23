<?php


namespace zukr\position;


use zukr\base\RecordHelper;

class PositionHelper extends RecordHelper
{
    /**
     * @var PositionHelper
     */
    private static $obj;
    /**
     * @var PositionRepository
     */
    private $positionRepository;

    /**
     * @return PositionHelper
     */
    public static function getInstance(): PositionHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
        }
        return static::$obj;

    }

    /**
     * @return PositionRepository
     */
    public function getPositionRepository(): PositionRepository
    {
        if ($this->positionRepository == null) {
            $this->positionRepository = new PositionRepository();
        }
        return $this->positionRepository;
    }
}