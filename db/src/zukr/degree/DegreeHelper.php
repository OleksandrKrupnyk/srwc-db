<?php


namespace zukr\degree;

use zukr\base\RecordHelper;

/**
 * Class DegreeHelper
 *
 * @package      zukr\degree
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class DegreeHelper extends RecordHelper
{

    /**
     * @var DegreeHelper
     */
    private static $obj;
    /**
     * @var DegreeRepository
     */
    private $degreeRepository;

    /**
     * @return DegreeHelper
     */
    public static function getInstance(): DegreeHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
        }
        return static::$obj;

    }

    /**
     * @return DegreeRepository
     */
    public function getDegreeRepository(): DegreeRepository
    {
        if ($this->degreeRepository === null) {
            $this->degreeRepository = new DegreeRepository();
        }

        return $this->degreeRepository;
    }
}