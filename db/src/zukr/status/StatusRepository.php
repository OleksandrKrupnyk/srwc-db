<?php


namespace zukr\status;

use zukr\base\AbstractRepository;
use zukr\base\Base;

/**
 * Class StatusRepository
 *
 * Репозиторій вчених статусів
 *
 * @package      zukr\status
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class StatusRepository extends AbstractRepository
{
    /**
     * @var string
     */
    protected $__className = Status::class;
    /**
     * @var array
     */
    private $statuses;

    /**
     * @return array|\MysqliDb
     */
    public function getDropDownList()
    {
        if ($this->statuses === null) {
            $statuses = Base::$app->cacheGetOrSet(get_called_class(), $this->getStatusesFormDB(), 3600);
            $this->statuses = $statuses;
        }
        return $this->statuses;
    }

    /**
     * @return array|\MysqliDb
     */
    private function getStatusesFormDB()
    {
        try {
            return Status::find()
                ->map('id')
                ->get(Status::getTableName(), null, ['id', 'statusfull']);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}