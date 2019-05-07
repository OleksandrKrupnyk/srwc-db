<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:59
 */

//print_r($_POST);
$_POST['id_u'] = (int)$_POST['id_u'];
$_POST['suname'] = trim(addslashes($_POST['suname']));
$_POST['name'] = trim(addslashes($_POST['name']));
$_POST['lname'] = trim(addslashes($_POST['lname']));
$_POST['position'];
$_POST['statusfull'];
$_POST['degree'];
$_POST['email'] = trim(addslashes($_POST['email']));
$_POST['tzmember'] = ($_POST['tzmember'] == "0")? "0":$_POST['tzmember'];
if (isset($_POST['phone'])) {
    $_POST['phone'] = trim($_POST['phone']);
} else $_POST['phone'] = '';
$arrival = (!isset($_POST['arrival']) || $_POST['arrival'] == "") ? 0 : 1;
$review = ($_POST['reviewer'] == "") ? 0 : 1;
$hash = md5($_POST['suname'].$_POST['name'].$_POST['lname']);
$query = "UPDATE `leaders` SET `id_u`='{$_POST['id_u']}',`id_tzmember`='{$_POST['tzmember']}'\n,`suname`='{$_POST['suname']}',`name`='{$_POST['name']}',`lname`='{$_POST['lname']}',`id_pos`='{$_POST['position']}',`id_sat`='{$_POST['statusfull']}',`id_deg`='{$_POST['degree']}',`arrival`='{$arrival}',`review`='{$review}',`phone` ='{$_POST['phone']}',`email` ='{$_POST['email']}',`date`=NOW(),`hash`='{$hash}'".
    "  WHERE `id`='{$_POST['id_l']}'";
//echo $query;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Полка оновлення запису дія leader_edit: " . mysqli_error($link));
log_action($_POST['action'], "leaders", $_POST['id_l']);
$url2go = ($_POST['from']) ? $_POST['from'] : "action.php?action=view";
Go_page($url2go);
//header("Location: action.php?action=view");
?>