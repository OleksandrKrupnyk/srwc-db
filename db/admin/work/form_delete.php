<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:08
 */
// удаление работы
global $link;
if (isset($_GET['id_w'])) {//Задан ли вообще номер работы?
    /*проверим связана работа с каким либо автором или нет
    Для этого определим количество этих связей в таблицах их не должно быть. Подсчитаем их и
    ровно удалим их */
    $id = (int)filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT);
    //Запрос на удаление с таблици связей работа-руководитель
    $query = "DELETE FROM `wl` WHERE `id_w`='{$id}';";

    $result = mysqli_query($link, $query) or die('Видалення запису з таблиці зв\'язків робота-керівник: ' . mysqli_error($link));
    log_action($_GET['action'], 'wl', $id);
    //Запрос на удаление с таблици связей работа-автор
    $query = "DELETE FROM `wa` WHERE `id_w`='{$id}';";

    $result = mysqli_query($link, $query) or die('Видалення запису з таблиці зв\'язків робота-автор: ' . mysqli_error($link));
    log_action($_GET['action'], 'wa', $id);
    $query = "DELETE FROM `works` WHERE `id`='{$id}';";

    $result = mysqli_query($link, $query) or die('Видалення запису з таблиці реестру робіт: ' . mysqli_error($link));
    log_action($_GET['action'], 'works', $id);
    Go_page('action.php?action=all_view');
}
