<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:55
 */
//проверка известен ли номер
//работы с которой связываем руководителя или автора
//Завершение обработки связывния таблиц
global $link;
if (isset($_POST['id_w'])) {
    // обработаем руководителей
    //передан ли массив руководителей (добалял ли пользователь таковых)
    if (isset($_POST['leader']) && $_POST['leader'] != '-1') {
        // получим список руководителей
        $leaders = $_POST['leader'];
        foreach ($leaders as $id_l) {
            $query = "INSERT INTO `wl` (`id_w`,`id_l`,`date`) "
                . "VALUE ('{$_POST['id_w']}','{$id_l}',NOW())";
            $result = mysqli_query($link, $query);
            log_action($_POST['action'], "wl", $_POST['id_w']);
            //or die("Помилка зв'язування керівника: " . mysqli_error($link));
        }
        // обработаем авторов
    }
    if (isset($_POST['autor']) && $_POST['autor'] != '-1') {
        $autors = $_POST['autor'];
        foreach ($autors as $id_a) {
            $query = "INSERT INTO `wa` (`id_w`,`id_a`,`date`) "
                . "VALUE ('{$_POST['id_w']}','{$id_a}',NOW())";
            $result = mysqli_query($link, $query);
            log_action($_POST['action'], "wa", $_POST['id_w']);
            // or die("Помилка зв'язування автора: " . mysqli_error($link));
        }
    }
    //перейдем к просмотру списка работ
    Go_page('action.php?action=all_view');
} else {
    Go_page('action.php');
}