<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:46
 */
//Добавление руководителя работы
global $link;
$_POST['id_u']    = (int)$_POST['id_u'];
$_SESSION['id_u'] = $_POST['id_u'];
$_POST['suname']  = trim(addslashes($_POST['suname']));
$_POST['name']    = trim(addslashes($_POST['name']));
$_POST['lname']   = trim(addslashes($_POST['lname']));
$_POST['position'];
$_POST['statusfull'];
$_POST['degree'];
$_POST['email'] = trim(addslashes($_POST['email']));
if (isset($_POST['phone'])) {
    $_POST['phone'] = trim($_POST['phone']);
} else {
    $_POST['phone'] = '';
}
$review = ($_POST['reviewer'] == "") ? 0 : 1;
$hash   = md5($_POST['suname'] . $_POST['name'] . $_POST['lname']);
$query  = "INSERT INTO `leaders`(`id_u`,`suname`,`name`,`lname`,`id_pos`,`id_sat`,`id_deg`,`arrival`,`review`,`phone`,`email`,`date`,`hash`)
                VALUES
                ('{$_POST['id_u']}','{$_POST['suname']}','{$_POST['name']}','{$_POST['lname']}','{$_POST['position']}','{$_POST['statusfull']}','{$_POST['degree']}','0',{$review},'{$_POST['phone']}','{$_POST['email']}',NOW(),'{$hash}')";
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
//print_r($query);
$result = mysqli_query($link, $query);
//or die("Полка запису дія leader_add: " . mysqli_error($link));
if (mysqli_error($link) == '') {
    $id = mysqli_insert_id($link);
    log_action($_POST['action'], "leaders", $id);
} else { // Выполнять если есть ошыбка
    $error_message .= AnalizeMysqlError(mysqli_error($link));
}
?>