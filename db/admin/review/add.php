<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:39
 */

// Добавление рецензии

use zukr\base\Base;
use zukr\log\Log;
use zukr\review\ReviewRepository;

$id_u = filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT);
$id_w = filter_input(INPUT_POST, 'Review', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY)['id_w'];

if (!$id_w || !$id_u) {
    Base::$session->setFlash('recordSaveMsg', 'Не вказана робота або ВНЗ');
    Base::$session->setFlash('recordSaveType', 'danger');
    Go_page('action.php?action=all_view');
}

$review = new \zukr\review\Review();
$review->load($_POST);

if (isset($_POST['save'])) {
    $url2go = 'action.php?action=review_edit&id=' . $review->id;
}
if (isset($_POST['save+exit'])) {
    $url2go = Base::$session->get('redirect_to');
}

if ($review->id_w) {
    $countOfReviews = (new ReviewRepository())->getCountOfReviewByWorkId($review->id_w);

    if ($countOfReviews > 1) {
        Base::$session->setFlash('recordSaveMsg', 'Достатьно рецензій для даної роботи');
        Base::$session->setFlash('recordSaveType', 'error');
    } else {
        $review->save();
        $log = Log::getInstance();
        $log->logAction(null, $review::getTableName(), $review->id);
    }
    Go_page($url2go);
}