<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 10.11.17
 * Time: 23:27
 */

// Редактирование рецензии

use zukr\base\Base;
use zukr\log\Log;
use zukr\review\Review;
use zukr\review\ReviewRepository;

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
/** @var Review $review */
$review = (new ReviewRepository())->findById($id);
if ($review === null) {
    Go_page('error');
}
$review->load($_POST);
$review->save();
$log = Log::getInstance();
$log->logAction(null, $review::getTableName(), $review->id);

if (isset($_POST['save'])) {
    $url2go = 'action.php?' . http_build_query(['action' => 'review_edit', 'id' => $review->id]);
} elseif (isset($_POST['save+exit'])) {
    $url2go = $url2go = Base::$session->get('redirect_to','action.php?' . http_build_query(['action' => 'all_view']));
}
Go_page($url2go);

