<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:44
 */

//Добавление автора работы


use zukr\author\Author;
use zukr\base\Base;
use zukr\log\Log;
use zukr\workauthor\WorkAuthor;

$_SESSION['id_u'] = $_POST['id_u'];

$autor = new Author();
$autor->load($_POST);
$save = $autor->save();
$log = Log::getInstance();
$log->logAction(null, $autor::getTableName(), $autor->id);
/*Если известно с какой работой связать то связать и перейти на страничку указания на работу*/
$workId = filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT);
if ($workId && $autor->id > 0) {
    $workAuthor = new WorkAuthor();
    $workAuthor->id_w = $workId;
    $workAuthor->id_a = $autor->id;
    $workAuthor->save();
    $log->logAction(null, $workAuthor::getTableName(), $workAuthor->id_w);
    $url2go = 'action.php?action=all_view#id_w' . $_POST['id_w'];
}
if (isset($_POST['save'])) {
    $url2go = 'action.php?action=author_edit&id_a=' . $autor->id;
}
if (isset($_POST['save+exit'])) {
    $url2go = Base::$session->get('redirect_to');
}
Go_page($url2go);