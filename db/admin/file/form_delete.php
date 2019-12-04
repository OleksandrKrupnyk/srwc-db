<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:09
 */
// удаление файла работ
global $link;
if (isset($_GET['id_w']) && isset($_GET['id_f'])) {
    //получим информацию по файлу из таблицы
    $fInfo = fullinfo("files", "id", $_GET['id_f']);
    //Попытаемся удалить файл!
    $fileIs = false; //Считаем что файла по умолчанию нет
    // сперва проверим есть ли он вообще в системе
    if (file_exists($fInfo['file'])) {//имя файла есть в латинице
        $fileIs = true;
    } else if (file_exists(iconv('UTF-8', 'windows-1251',$fInfo['file']))) {//имя файла  есть в кирилице
        $fileIs = true;
        $fInfo['file'] = iconv('UTF-8', 'windows-1251',$fInfo['file']);
    }

    if ($fileIs) {
        if (!unlink($fInfo['file'])) $error_message .= "Помилка видалення файлу\n";
        $error_message .= "Файл успішно видалено з системи\n";
        unset($fInfo);
        $query = "DELETE FROM `files` WHERE `id`='" . $_GET['id_f'] . "';";
        //$error_message .= $query."\n";
        $result = mysqli_query($link, $query) or die("Видалення запису з таблиці реєстру файлів: " . mysqli_error($link));
        log_action($_GET['action'], "files", $_GET['id_w']);
    }
    else {
        $error_message .= "Файл відсутній\n";
    }
    //Закоментировать для отладки
    header("Location: action.php?action=all_view#id_w" . $_GET['id_w']);
}

?>