<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:59
 */

//Редактирование руководителя

use zukr\base\Base;
use zukr\leader\Leader;
use zukr\leader\LeaderRepository;
use zukr\log\Log;

$_SESSION['id_u'] = $_POST['id_u'];
$id_l = filter_input(INPUT_POST, 'id_l', FILTER_VALIDATE_INT);
/** @var Leader $leader */
$leader = (new LeaderRepository())->findById($id_l);

if ($leader === null) {
    Go_page('error');
}
$leader->load($_POST);
$save = $leader->save();
$log = Log::getInstance();
$log->logAction(null, $leader::getTableName(), $leader->id);

if (isset($_POST['save'])) {
    $url2go = 'action.php?action=leader_edit&id_l=' . $leader->id;
}elseif (isset($_POST['save+exit'])) {
    $url2go = $url2go = Base::$session->get('redirect_to');
}
Go_page($url2go);