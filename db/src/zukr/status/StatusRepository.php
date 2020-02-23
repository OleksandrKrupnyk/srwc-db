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
        $statusesList = $this->getStatuses();
        $statuses = [];
        if (!empty($statusesList)) {
            foreach ($statuses as $id => $s) {
                $statuses[$id] = $s['statusfull'];
            }
        }
        return $statuses;
    }

    /**
     * @return array|mixed
     */
    public function getStatuses()
    {
        if ($this->statuses === null) {
            $statuses = Base::$app->cacheGetOrSet(
                Status::class,
                function () {
                    return $this->getStatusesFormDB();
                },
                3600);
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
                ->get(Status::getTableName());
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}