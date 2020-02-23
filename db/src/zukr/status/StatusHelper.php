<?php


namespace zukr\status;


use zukr\base\RecordHelper;

/**
 * Class StatusHelper
 *
 * @package      zukr\status
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class StatusHelper extends RecordHelper
{
    /**
     * @var StatusHelper
     */
    private static $obj;
    /**
     * @var StatusRepository
     */
    private $statusRepository;

    /**
     * @return StatusHelper
     */
    public static function getInstance(): StatusHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
        }
        return static::$obj;

    }

    /**
     * @return StatusRepository
     */
    public function getStatusRepository(): StatusRepository
    {
        if ($this->statusRepository === null) {
            $this->statusRepository = new StatusRepository();
        }
        return $this->statusRepository;
    }

}