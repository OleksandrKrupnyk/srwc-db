<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:06
 */
//
//print_r($_GET);
// проверяем заданы ли нам нужные данные
global $link;
if ((isset($_GET['id_a']) || isset($_GET['id_l'])) && isset($_GET['id_w'])) {
    //Таблица из которой удалем
    $table = (isset($_GET['id_l'])) ? 'wl' : 'wa';
    //Формируем запрос
    $query = "DELETE FROM `{$table}` WHERE `id_w`='{$_GET['id_w']}' AND ";
    switch ($table) {
        case "wl":
            $query .= "`id_l` ='{$_GET['id_l']}'";
            break;
        case "wa":
            $query .= "`id_a` ='{$_GET['id_a']}'";
            break;
    }//завершение switch
    //echo $query;
    $result = mysqli_query($link, $query)
    or die("Видалення запису з таблиці зв'язків: " . mysqli_error($link));
    log_action($_GET['action'], $table, $_GET['id_w']);
    //Если все прекрасно показать табличку
    header("Location: action.php?action=all_view#id_w" . $_GET['id_w']);
}
?>