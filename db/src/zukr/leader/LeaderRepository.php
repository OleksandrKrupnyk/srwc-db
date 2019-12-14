<?php


namespace zukr\leader;

use zukr\base\AbstractRepository;
use zukr\base\Base;

/**
 * Class LeaderRepository
 *
 * @package      zukr\leader
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class LeaderRepository extends AbstractRepository
{

    public $__className = Leader::class;

    /**
     * Усіх окрім рецензентів
     *
     * @param int $id
     * @return array|\MysqliDb
     */
    public function getNotReviewById(int $id): array
    {
        $table = $this->model::getTableName();
        try {
            return $this->model::find()
                ->where('id', $id)
                ->where('review', 0)
                ->get($table);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }


}