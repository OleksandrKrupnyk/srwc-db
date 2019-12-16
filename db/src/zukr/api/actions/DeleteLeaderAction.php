<?php


namespace zukr\api\actions;

use zukr\leader\Leader;
use zukr\leader\LeaderRepository;
use zukr\log\Log;
use zukr\review\ReviewRepository;
use zukr\workleader\WorkLeaderRepository;

/**
 * Class DeleteLeaderAction
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class DeleteLeaderAction implements ApiActionsInterface
{
    /** @var int */
    public $id;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $workAuthorData = (new WorkLeaderRepository())->getByLeaderId($this->id);
            if (!empty($workAuthorData)) {
                throw new \Exception('Керівник звязаний з роботою', 610);
            }
            $leaderData = (new LeaderRepository())->getById($this->id);
            if (empty($leaderData)) {
                throw new \Exception('Не можу отримати запис керівника', 615);
            }
            if ($leaderData['review'] === '1') {
                throw new \Exception('Керівник є рецензентом', 610);
            }

            if ($this->isLeaderAuthorOfReviews()) {
                throw new \Exception('Керівник є автором рецензії(й)', 610);
            }

            $leader = new Leader();
            $leaderQuery = $leader->getDb();
            $leaderQuery->startTransaction();
            $leaderQuery->where('id', $this->id);
            $delete = $leader->delete($leaderQuery);
            $log = Log::getInstance();
            $log->logAction('Delete-Leader-Action', 'leaders', $this->id);
            ($delete) ? $leaderQuery->commit() : $leaderQuery->rollback();
            $response = ['msg' => 'Запис керівника видалено', 'code' => 0];
        } catch (\Exception $e) {
            if ($leaderQuery !== null) {
                $leaderQuery->rollback();
            }
            $response = ['msg' => $e->getMessage(), 'code' => $e->getCode()];
        }
        echo json_encode($response);

    }

    /**
     * @inheritDoc
     */
    public function init(array $params = [])
    {
        $this->id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    }


    private function isLeaderAuthorOfReviews(): bool
    {
        $oneReview = (new ReviewRepository())->getOneReviewByReviewerId($this->id);
        return is_array($oneReview) && !empty($oneReview);
    }
}