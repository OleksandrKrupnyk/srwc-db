<?php


namespace zukr\review;


use zukr\base\AbstractRepository;
use zukr\base\Base;

/**
 * Class ReviewRepository
 *
 * @package      zukr\review
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ReviewRepository extends AbstractRepository
{
    /**
     * @var string
     */
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
     * @return array|null
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
     * Количество рецензий на работу
     *
     * @param int $workId ІД работи
     * @return int|null Количестов рецензий на работу
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

    /**
     * @return array
     */
    public function getSumOfBalls(): array
    {
        try {
            $db = $this->model->getDb();
            $woksBalls = $db->rawQuery("
SELECT `reviews`.`id`, 
       `reviews`.`id_w`, 
       leaders.id_tzmember, 
       reviews.conclusion,
       `leaders`.`suname`,
       `leaders`.`name`,
       `leaders`.`lname`,
       (`actual`+`original`+`methods`+`theoretical`+`practical`+`literature`+`selfcontained`+`design`+`publication`+`government`+`tendentious`) AS sumball 
              FROM `reviews` 
              JOIN `leaders` ON `leaders`.`id` = `reviews`.`review1`");
            return $woksBalls ?? [];
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }


    }


}