<?php


namespace zukr\review;


use zukr\base\AbstractRepository;
use zukr\base\Base;

class ReviewRepository extends AbstractRepository
{

    public $__className = Review::class;

    /**
     * Рецензия по Id Рецензента
     *
     * @param int $reviewerId
     * @return array|\MysqliDb
     */
    public function getReviewsByReviewerId(int $reviewerId)
    {

        $table = $this->model::getTableName();
        try {
            return $this->model::find()
                ->where('review1', $reviewerId)
                ->get($table);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

    /**
     * @param int $reviewerId
     * @return
     */
    public function getOneReviewByReviewerId(int $reviewerId): ?array
    {
        $table = $this->model::getTableName();
        try {
            $result = $this->model::find()
                ->where('review1', $reviewerId)
                ->getOne($table);
            return $result;
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return null;
        }

    }

    /**
     * @param int $workId
     * @return int|null
     */
    public function getCountOfReviewByWorkId(int $workId): ?int
    {
        try {
            $this->model::find()
                ->where('id_w', $workId)->withTotalCount()
                ->get($this->model::getTableName(), null, 'id_w');
            return (int)$this->model::find()->totalCount;
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return null;
        }
    }


}