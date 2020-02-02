<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:52
 */

//Добавление всех ведомостей
use zukr\author\Author;
use zukr\base\Base;
use zukr\leader\Leader;
use zukr\log\Log;
use zukr\work\Work;
use zukr\workauthor\WorkAuthor;
use zukr\workleader\WorkLeader;


$id_a = filter_input(INPUT_POST, 'id_a', FILTER_VALIDATE_INT);
$id_l = filter_input(INPUT_POST, 'id_l', FILTER_VALIDATE_INT);
$work = new Work();
$work->load($_POST);

$work->save();
$log = Log::getInstance();
$log->logAction(null, $work::getTableName(), $work->id);
Base::$session->set('id_u', $work->id_u);
$workId = $work->id;

if ($id_a === Work::PERSON_EXIST) {
    $author = new Author();
    $author->load($_POST);
    $author->save();
    $autorId = $author->id;
    $log->logAction('author_add', $author::getTableName(), $work->id);
} else {
    $autorId = $id_a;
}

if ($id_l === Work::PERSON_EXIST) {
    $leader = new Leader();
    $leader->load($_POST);
    $leader->save();
    $log->logAction('leader_add', $leader::getTableName(), $work->id);
    $leaderId = $leader->id;
} else {
    $leaderId = $id_l;
}

$wa = new WorkAuthor();
$wa->load(['id_w' => $workId, 'id_a' => $autorId], false);
$wa->save();
$log->logAction('work_link', $wa::getTableName(), $work->id);

$wl = new WorkLeader();
$wl->load(['id_w' => $workId, 'id_l' => $leaderId], false);
$wl->save();
$log->logAction('work_link', $wl::getTableName(), $work->id);
$page = 'action.php?' . http_build_query(['action' => 'all_view']) . '&#id_w' . $workId;
Go_page($page);