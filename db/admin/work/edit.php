<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:01
 */

//Редактирование работы
use zukr\log\Log;
use zukr\work\WorkRepository;

$id_w = filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT);
$work = (new WorkRepository())->findById($id_w);
if ($work === null || !$id_w) {
    Go_page('error');
}
$work->load($_POST);
$work->save();
$log = Log::getInstance();
$log->logAction(null, $work::getTableName(), $work->id);
$url2go = $_POST['FROM'] ?: 'action.php';
if (isset($_POST['save'])) {
    $url2go = 'action.php?action=work_edit&id_w=' . $work->id;
}
if (isset($_POST['save+exit'])) {
    $url2go = $_POST['from'] ?: 'action.php?action=all_view#id_w' . $work->id;
}
Go_page($url2go);
