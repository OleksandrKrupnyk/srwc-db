<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 11.11.17
 * Time: 2:16
 */
// удаление рецензи
global $link;
if (isset($_GET['id']) && isset($_GET['id_w'])) {
    $query = "DELETE FROM `reviews` WHERE `id`='{$_GET['id']}'";
    $result = mysqli_query($link, $query) or die("Видалення запису з таблиці реестру  рецензій: " . mysqli_error($link));
    log_action($_GET['action'], "reviews", $_GET['id_w']);
    header("Location: action.php?action=all_view#id_w" . $_GET['id_w']);
}
