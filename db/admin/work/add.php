<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:50
 */

//Добавление данных работы
use zukr\base\Base;
use zukr\log\Log;
use zukr\work\Work;

$work = new Work();
$work->load($_POST);
$work->save();
$log = Log::getInstance();
$log->logAction(null, $work::getTableName(), $work->id);
$url2go = $_POST['FROM'] ?: 'action.php';
if (isset($_POST['save'])) {
    $url2go = 'action.php?action=work_edit&id_w=' . $work->id;
}
if (isset($_POST['save+exit'])) {
    $url2go =  Base::$session->get('redirect_to');
}
Go_page($url2go);