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

    /**
     * @param int $workId
     * @param int $univerId
     * @return array
     */
    public function getListAvailableReviewersForWork(int $workId, int $univerId): array
    {
        try {
            return $this->model::find()
                ->rawQuery('
SELECT l.id,
       l.suname, l.name, l.lname,p.position, deg.degree,
       statuses.status, u.univer
FROM leaders as l
         JOIN positions as p ON l.id_pos =p.id
         JOIN degrees as deg ON l.id_deg = deg.id
         JOIN statuses ON l.id_sat = statuses.id
         JOIN univers as u ON l.id_u=u.id
WHERE l.review = TRUE
  AND l.id_u <> ?
  AND NOT(
            l.id = (SELECT r.review1 FROM reviews as r WHERE r.id_w= ?)
        AND
            (SELECT r.review1 FROM reviews as r WHERE r.id_w= ?) IS NOT NULL
    )
ORDER BY suname;', [$univerId, $workId, $workId]);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

    /**
     * @param int $workId
     * @param int $univerId
     * @param int $currentReviewerId
     * @return array
     */
    public function getListAvailableEditableReviewersForWork(int $workId, int $univerId,int $currentReviewerId): array
    {
        try {
            return $this->model::find()
                ->rawQuery('
SELECT l.id,
       l.suname, l.name, l.lname,p.position, deg.degree,
       statuses.status, u.univer
FROM leaders as l
         JOIN positions as p ON l.id_pos =p.id
         JOIN degrees as deg ON l.id_deg = deg.id
         JOIN statuses ON l.id_sat = statuses.id
         JOIN univers as u ON l.id_u=u.id
WHERE l.review = TRUE
  AND l.id_u <> ?
  AND 
            l.id <> (SELECT r.review1 FROM reviews as r WHERE r.id_w= ? AND r.review1 <> ?)
ORDER BY suname;', [$univerId, $workId, $currentReviewerId]);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

    /**
     * @return int
     */
    public function getCountInvitedLeaders(): int
    {
        try {
            $this->model::find()->withTotalCount()
                ->where('invitation', Leader::KEY_ON)
                ->get($this->model::getTableName(), null, 'id');
            return $this->model::find()->totalCount;
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return 0;
        }

    }

    /**
     * @param int $id ІД користувача в системі
     * @return array Данні користувача
     */
    public function getByTzMemberId(int $id): array
    {
        try {
            $r = $this->model::find()
                ->where('id_tzmember', $id)
                ->getOne($this->model::getTableName());
            return $r ?? [];
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}