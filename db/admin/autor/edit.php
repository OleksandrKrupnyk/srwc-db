<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:57
 */

//Изменение данных автора
use zukr\author\AuthorRepository;
use zukr\log\Log;

$_SESSION['id_u'] = $_POST['id_u'];
$id_a = filter_input(INPUT_POST, 'id_a', FILTER_VALIDATE_INT);
$author = AuthorRepository::findById($id_a);
if ($author === null) {
    Go_page('action.php?action=error_list');
    exit();
}
$author->load($_POST);
$author->save();
$log = Log::getInstance();
if ($autor->id > 0) {
    $log->logAction(null, $autor::getTableName(), $autor->id);
}
if (isset($_POST['save'])) {
    $url2go = "action.php?action=autor_edit&id_a=" . $id_a;
}
if (isset($_POST['save+exit'])) {
    $url2go = ($_POST['from']) ? $_POST['from'] : "action.php?action=all_view";
}
Go_page($url2go);