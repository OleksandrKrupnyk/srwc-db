<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:44
 */
//Добавление автора работы
global $link;
$_POST['id_u'] = (int)$_POST['id_u'];
$setId_w       = false;
if (isset($_POST['id_w'])) {
    $_POST['id_w'] = (int)$_POST['id_w'];
    $setId_w       = true;
}

$_SESSION['id_u'] = $_POST['id_u'];
//Добавить проверку почты
var_dump($_POST);die();
$_POST['suname'] = trim(addslashes($_POST['suname']));
$_POST['name']   = trim(addslashes($_POST['name']));
$_POST['lname']  = trim(addslashes($_POST['lname']));
$_POST['curse']  = (int)$_POST['curse'];
$_POST['email']  = trim(addslashes($_POST['email']));
if (isset($_POST['phone'])) {
    $_POST['phone'] = trim($_POST['phone']);
} else {
    $_POST['phone'] = '';
}
$hash  = md5($_POST['suname'] . $_POST['name'] . $_POST['lname']);
$query = "INSERT INTO `autors` (`id_u`,`suname`,`name`,`lname`, `curse`,`email`,`active`,`arrival`,`phone`,`date`,`hash`)\n"
    . "VALUES ('{$_POST['id_u']}','{$_POST['suname']}','{$_POST['name']}','{$_POST['lname']}','{$_POST['curse']}','{$_POST['email']}','0','0','{$_POST['phone']}',NOW(),'{$hash}')";
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Полка запису дія autor_add: " . mysqli_error($link));
$id_a = mysqli_insert_id($link);
log_action($_POST['action'], "autors", $id_a);
$url2go = ($_POST['FROM']) ? $_POST['FROM'] : "action.php";
/*Если известно с какой работой связать то связать и перейти на страничку указания на работу*/
if ($setId_w = true) {
    $query = "INSERT INTO `wa` (id_w,id_a,date) VALUE ('{$_POST['id_w']}','{$id_a}',NOW())";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query);
    log_action($_POST['action'], "wa", $_POST['id_w']);
    $url2go = "action.php?action=all_view#id_w" . $_POST['id_w'];
}
Go_page($url2go);