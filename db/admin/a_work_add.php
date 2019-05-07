<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:50
 */
$_POST['id_u'] = (int)$_POST['id_u'];
$_SESSION['id_u'] = $_POST['id_u'];
$_POST['title'] = trim(addslashes($_POST['title']));
$_SESSION['title'] = $_POST['title'];
$_POST['motto'] = trim(addslashes($_POST['motto']));
$_POST['public'] = trim(addslashes($_POST['public']));
$_POST['introduction'] = trim(addslashes($_POST['introduction']));
$_POST['section'] = (int)$_POST['section'];
$tesis = ($_POST['tesis'] == "") ? 0 : 1;
$dead = ($_POST['dead'] == "") ? 0 : 1;
$_POST['comments'] = trim(addslashes($_POST['comments']));
$query = "INSERT INTO `works` (`id_u`,`title`,`motto`,`id_sec`,`public`,`introduction`,`tesis`,`dead`,`date`,`comments`)
                    VALUES ('{$_POST['id_u']}','{$_POST['title']}','{$_POST['motto']}','{$_POST['section']}','{$_POST['public']}','{$_POST['introduction']}','{$tesis}','{$dead}',NOW(),'{$_POST['comments']}')";
//echo $query;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Полка запису дія work_add: " . mysqli_error($link));
$id = mysqli_insert_id($link);
log_action($_POST['action'], "works", $id);
?>