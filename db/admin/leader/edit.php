<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:59
 */

//Редактирование руководителя

use zukr\leader\Leader;
use zukr\leader\LeaderRepository;
use zukr\log\Log;

$_SESSION['id_u'] = $_POST['id_u'];
$id_l = \filter_input(INPUT_POST, 'id_l', FILTER_VALIDATE_INT);
/** @var Leader $leader */
$leader = (new LeaderRepository())->findById($id_l);

if ($leader === null) {
    Go_page('action.php?action=error_list');
    exit();
}
$leader->load($_POST);
$save = $leader->save();
if ($save) {
    $_SESSION['notify']['msg'] = "Запис було збережено";
    $_SESSION['notify']['type'] = 'info';
}
$log = Log::getInstance();
$log->logAction(null, $leader::getTableName(), $leader->id);
if (isset($_POST['save'])) {
    $url2go = 'action.php?action=leader_edit&id_l=' . $leader->id;
}
if (isset($_POST['save+exit'])) {
    $url2go = ($_POST['from']) ? $_POST['from'] : 'action.php?action=all_view';
}
Go_page($url2go);