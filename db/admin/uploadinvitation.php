<?php

use zukr\base\Base;

/**
 * @author    studyjquery
 * @copyright 2014
 */
require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';

header("Content-Type: text/html; charset=utf-8");
session_name('tzLogin');
session_start();
global $link;
global $FROM;

switch (filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING)) {
    case "upload_invitatation_file":
        { // загрузить файл сканированых листов
            //print_r($_POST);
            //print_r($_FILES);
            if (isset($_FILES['file']))//проверяем загрузился ли файл
            {
                $file = $_FILES['file']['tmp_name'];
                $file_name = $_FILES['file']['name'];
                $file_size = $_FILES['file']['size'];
                $file_type = $_FILES['file']['type'];
                if (is_uploaded_file($file)) {
                    $file_md5 = md5_file($file);
                    //echo $file_md5;
                }
                $error_code = $_FILES['file']['error'];
                $id_u = ($_POST['id_u'] == '') ? '0' : $_POST['id_u'];
                if ($error_code == 0)//Нет ли ошибок загрузки
                {
                    //проверим есть ли католог для вуза
                    //если нет то создадим его
                    if (!file_exists(IMGDIR . $id_u)) {
                        if (!mkdir($concurrentDirectory = IMGDIR . $id_u . "/", 0777, true) && !is_dir($concurrentDirectory)) {
                            die('Помилка при створенні теки для матеріалів роботи...');
                        }
                    }
                    //если он есть то удостоверимся что это каталог
                    if (is_dir(IMGDIR . $id_u)) {
                        //да это каталог
                        //Сфорируем путь для копиования файла
                        $file_name = IMGDIR . $id_u . "/" . date('Ymd_His') . '_' . $file_name;
                        //echo $file_name;
                        //скопируем туда файл
                        if (!copy($file, $file_name)) {
                            echo "Помилка при копіюванні файлу";
                        } else {//сформируем запрос в БД
                            $query = "INSERT INTO `scanfiles` (`id_u`,`file`,`filename`,`md5sum`,`date`)\n"
                                . "VALUES ( '{$id_u}','" . htmlspecialchars($file_name) . "','Файл запрошення " . basename($file_name) . "','{$file_md5}',NOW())";
                            mysqli_query($link, "SET NAMES 'utf8'");
                            mysqli_query($link, "SET CHARACTER SET 'utf8'");
                            $result = mysqli_query($link, $query)
                            or die("Полка запису дія upload_invitatation_file: " . mysqli_error($link));
                            log_action($_POST['action'], "scanfiles", $id_u);
                            //header("Location: uploadinvitation.php");
                        }
                    }

                }
            }
        }
        break;

}//окончание swtch

// Удаление файла запрошення
switch (filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING)) {
    case "delete_file":
        {//удаление файла приглашения
            if (isset($_GET['id_u']) && isset($_GET['id_f'])) {
                //получим информацию по файлу из таблицы
                $fInfo = fullinfo("scanfiles", "id", $_GET['id_f']);
                //Попытаемся удалить файл сперва проверим есть ли он вообще в системе
                if (file_exists($fInfo['file'])) {
                    if (!unlink($fInfo['file'])) {
                        echo "Помилка видалення файлу";
                    }
                    //echo "Delete file complite!\n";
                    unset($fInfo);
                    $query = "DELETE FROM `scanfiles` \n"
                        . "WHERE `id`='" . $_GET['id_f'] . "';";
                    //echo $query;
                    $result = mysqli_query($link, $query) or die("Видалення запису з таблиці реестру cканувань: " . mysqli_error($link));
                    log_action($_GET['action'], "scanfiles", $_GET['id_u']);
                }
                header("Location: uploadinvitation.php");
                exit();
            }
        }
        break; //удаление файла работы
}//Обработка switch для GET
$query = "SELECT  `scanfiles` . * ,  `univerrod` 
    FROM  `scanfiles` 
    LEFT JOIN  `univers` ON  `scanfiles`.`id_u` =  `univers`.`id`";
$result = mysqli_query($link, $query)
or die("Помилка зчитування : " . mysqli_error($link));
$count = mysqli_num_rows($result);
if ($count > 0) {
    $str = '<ol>';
    while ($row = mysqli_fetch_array($result)) {
        //$str2=explode("/",$row['file']);
        //$str2=end($str2);

        $str .= "<li>Для {$row['univerrod']} <a href=\"{$row['file']}\">{$row['filename']}</a>&nbsp;"
            . "<a href=\"uploadinvitation.php?action=delete_file&id_u={$row['id_u']}&id_f={$row['id']}\" title=\"Видалити файл\"></a></li>";
        unset($str2);
    }
    $str .= "</ol>";
} else {
    $str = "<p><mark>Нема файлів для відображення</mark></p>";
}
?>
<!DOCTYPE html>
<html lang="ua">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="../css/style.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>
    <title><?= Base::$app->app_name ?></title>
</head>
<body>
<header><a href="action.php">Меню</a></header>
<h1>Список відсканованих запрощень</h1>
<form class="form"><?= $str ?></form>
<form class="addScanFiles form" enctype="multipart/form-data" method="post" action="uploadinvitation.php">
    <fieldset>
        <legend>Завантаження сканованих запрошень</legend>
        <label>ВНЗ:</label> <?= list_univers(1, 1, 1) ?>
        <label>Файл:</label>
        <input type="file" name="file" size="20">
        <input type="submit" value="Завантажити">
        <input type="hidden" name="action" value="upload_invitatation_file">
    </fieldset>
</form>
<div id="operator">Оператор :<span><?= Base::$user->getUser()->getLogin() ?></span>
    <span><?= Base::$user->getUser()->isAdmin() ? 'A' : '' ?></span>
    <span><?= Base::$user->getUser()->isReview() ? 'R' : '' ?></span>
</div>
<autor class="autor">Krupnik&copy;</autor>
</body>
</html>
