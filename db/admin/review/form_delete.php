<?php
// удаление рецензии
use zukr\base\Base;
use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\exceptions\UnauthorizedAccessException;
use zukr\log\Log;
use zukr\review\Review;

try {
    if (empty($id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT))) {
        throw new InvalidArgumentException('id Must be set');
    }
    if (empty($id_w = filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT))) {
        throw new InvalidArgumentException('id_w Must be set');
    }

    $log = Log::getInstance();
    if (!Base::$user->getUser()->isAdmin()) {
        throw new UnauthorizedAccessException(__CLASS__ . '::' . __METHOD__ . 'User with id: ' . $userId . ' try to make delete action');
    }
    $review = new Review();
    $queryReview = $review->getDb();
    $queryReview->startTransaction();
    $queryReview->where('id', $id);
    $delete = $review->delete($queryReview);
    if ($delete) {
        $log->logAction('delete_review', $review::getTableName(), $id);
    }
    ($delete) ? $queryReview->commit() : $queryReview->rollback();
    $url2go = 'action.php?action=all_view#id_w' . $id_w;
} catch (\Exception $e) {
    if ($queryReview !== null) {
        $queryReview->rollback();
    }
    $url2go = 'error';
    Base::$log->error($e->getMessage());
} finally {
    Go_page($url2go);
}
