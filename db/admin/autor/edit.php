<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:57
 */

//Изменение данных автора
use zukr\author\AuthorRepository;
use zukr\base\Base;
use zukr\log\Log;

$_SESSION['id_u'] = $_POST['id_u'];
$id_a = filter_input(INPUT_POST, 'id_a', FILTER_VALIDATE_INT);
$author = (new AuthorRepository())->findById($id_a);
if ($author === null) {
    Go_page('error');
}
$author->load($_POST);
$save = $author->save();

$log = Log::getInstance();
if ($autor->id > 0) {
    $log->logAction(null, $autor::getTableName(), $autor->id);
}
if (isset($_POST['save'])) {
    $url2go = 'action.php?action=autor_edit&id_a=' . $id_a;
}
if (isset($_POST['save+exit'])) {
    $url2go = Base::$session->get('redirect_to');
}
Go_page($url2go);