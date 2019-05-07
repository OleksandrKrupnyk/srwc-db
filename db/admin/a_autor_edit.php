<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:57
 */
$_POST['id_u'] = (int)$_POST['id_u'];
$_SESSION['id_u'] = $_POST['id_u'];
//Добавить проверку почты
$_POST['suname'] = trim(addslashes($_POST['suname']));
$_POST['name'] = trim(addslashes($_POST['name']));
$_POST['lname'] = trim(addslashes($_POST['lname']));
$_POST['curse'] = (int)$_POST['curse'];
$arrival = (!isset($_POST['arrival']) || $_POST['arrival'] == "") ? 0 : 1;
$bprint = (!isset($_POST['bprint']) || $_POST['bprint'] == "") ? 0 : 1;
$_POST['email'] = trim(addslashes($_POST['email']));
$_POST['place'] = trim(addslashes($_POST['place']));
$_POST['place'] = ($_POST['place']=='')?"D":$_POST['place'];


if (isset($_POST['phone'])) {
    $_POST['phone'] = trim($_POST['phone']);
} else $_POST['phone'] = '';
$hash = md5($_POST['suname'] . $_POST['name'] . $_POST['lname']);
$query = "UPDATE `autors` SET `id_u`='{$_POST['id_u']}',`suname`='{$_POST['suname']}',`name`='{$_POST['name']}',`lname`='{$_POST['lname']}',
                `curse`='{$_POST['curse']}',`email`='{$_POST['email']}',`place`='{$_POST['place']}',`arrival`='{$arrival}',`phone` ='{$_POST['phone']}',`bprint`='{$bprint}',`date`=NOW(),`hash`='{$hash}'"
    . " WHERE `id`='{$_POST['id_a']}'";
//echo $query;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Полка запису дія autor_edit: " . mysqli_error($link));
log_action($_POST['action'], "autors", $_POST['id_a']);
$url2go = ($_POST['from']) ? $_POST['from'] : "action.php?action=view";
Go_page($url2go);
//header("Location: action.php?action=view");
?>