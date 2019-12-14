<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:46
 */

//Добавление руководителя работы

use zukr\base\Base;
use zukr\leader\Leader;
use zukr\log\Log;

$log = Log::getInstance();
$_SESSION['id_u'] = $_POST['id_u'];
$leader = new Leader();
$leader->load($_POST);
$save = $leader->save();
$log->logAction(null, $leader::getTableName(), $leader->id);
if (isset($_POST['save'])) {
    $url2go = 'action.php?action=leader_edit&id_l=' . $leader->id;
}
if (isset($_POST['save+exit'])) {
    $url2go = $url2go = Base::$session->get('redirect_to');
}
Go_page($url2go);