<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:01
 */

//print_r($_POST);
if (isset($_POST['id_w'])) {
    $_POST['id_u'] = (int)$_POST['id_u'];
    $_SESSION['id_u'] = $_POST['id_u'];
    $_POST['title'] = trim(addslashes($_POST['title']));
    $_SESSION['title'] = $_POST['title'];
    $_POST['motto'] = trim(addslashes($_POST['motto']));
    $_POST['public'] = trim(addslashes($_POST['public']));
    $_POST['introduction'] = trim(addslashes($_POST['introduction']));
    $_POST['section'] = (int)$_POST['section'];
    $tesis = (!isset($_POST['tesis']) || $_POST['tesis'] == "") ? 0 : 1;
    $dead = (!isset($_POST['dead']) || $_POST['dead'] == "") ? 0 : 1;
    $arrival = (!isset($_POST['arrival']) || $_POST['arrival'] == "") ? 0 : 1;
    $invitation = (!isset($_POST['invitation']) || $_POST['invitation'] == "") ? 0 : 1;
    $_POST['comments'] = trim(addslashes($_POST['comments']));
    $query = "UPDATE `works` SET `id_u`='{$_POST['id_u']}',`title`='{$_POST['title']}',`motto`='{$_POST['motto']}',`id_sec`='{$_POST['section']}',`public`='{$_POST['public']}',`introduction`='{$_POST['introduction']}',
                        `arrival`='{$arrival}',`tesis`='{$tesis}',`dead`='{$dead}',`invitation` ='{$invitation}',`date`=NOW(),`comments`='{$_POST['comments']}'"
        ." WHERE `id`='{$_POST['id_w']}'";
    //echo $query;
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Полка оновлення запису дія work_edit: " . mysqli_error($link));
    log_action($_POST['action'], "works", $_POST['id_w']);
    header("Location: action.php?action=view#id_w" . $_POST['id_w']);
}
?>