<?php
/**
 * @author studyjquery
 * @copyright 2015
 *SHOW_PROGRAMA
 */
require 'config.inc.php';
require 'functions.php';
header("Content-Type: text/html; charset=utf-8");
session_name('tzLogin');
session_start();
global $link;
//Если есть доступ к странице
?>
<?php
if ($_SESSION['access']) {
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="../css/style.css" type="text/css" rel="stylesheet"/>
    <link href="../css/jquery-ui-1.10.3.custom.min.css" type="text/css" rel="stylesheet"/>
    <script language="javascript" type="text/javascript" src="../js/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="../js/jquery-ui-1.10.js"></script>
    <script language="javascript" type="text/javascript" src="../js/admin.js"></script>
    <title>Налаштування</title>
</head>
<body>
    <?php
    if (isset($_POST['SAVE_SETTINGS'])) {
        $value = ($_POST['SHOW_DB_TABLE'] == "") ? 0 : 1;
        $query = "UPDATE `settings` SET";
        $query .= " `value` = '{$value}'";
        $query .= " WHERE `parametr` = 'SHOW_DB_TABLE'";
        $result = mysqli_query($link, $query)
        or die("Помилка оновлення налаштувань: " . mysqli_error($link) . " SHOW_DB_TABLE");
        
        $value = ($_POST['SHOW_PROGRAMA'] == "") ? 0 : 1;
        $query = "UPDATE `settings` SET";
        $query .= " `value` = '{$value}'";
        $query .= " WHERE `parametr` = 'SHOW_PROGRAMA'";
        $result = mysqli_query($link, $query)
        or die("Помилка оновлення налаштувань: " . mysqli_error($link) . " SHOW_PROGRAMA");


        $value = ($_POST['PRINT_DDTU_HEADER'] == "") ? 0 : 1;
        $query = "UPDATE `settings` SET";
        $query .= " `value` = '{$value}'";
        $query .= " WHERE `parametr` = 'PRINT_DDTU_HEADER'";
        $result = mysqli_query($link, $query)
        or die("Помилка оновлення налаштувань: " . mysqli_error($link) . "PRINT_DDTU_HEADER");

        $value = ($_POST['SHOW_RAITING'] == "") ? 0 : 1;
        $query = "UPDATE `settings` SET";
        $query .= " `value` = '{$value}'";
        $query .= " WHERE `parametr` = 'SHOW_RAITING'";
        $result = mysqli_query($link, $query)
        or die("Помилка оновлення налаштувань: " . mysqli_error($link) . "SHOW_RAITING");

        $value = ($_POST['ALLOW_EMAIL'] == "") ? 0 : 1;
        $query = "UPDATE `settings` SET";
        $query .= " `value` = '{$value}'";
        $query .= " WHERE `parametr` = 'ALLOW_EMAIL'";
        $result = mysqli_query($link, $query)
        or die("Помилка оновлення налаштувань: " . mysqli_error($link) . "ALLOW_EMAIL");

        $value = ($_POST['INVITATION'] == "") ? 0 : 1;
        $query = "UPDATE `settings` SET";
        $query .= " `value` = '{$value}'";
        $query .= " WHERE `parametr` = 'INVITATION'";
        $result = mysqli_query($link, $query)
        or die("Помилка оновлення налаштувань: " . mysqli_error($link) . "INVITATION");


        $value = ($_POST['SHOW_FILES_LINK'] == "") ? 0 : 1;
        $query = "UPDATE `settings` SET";
        $query .= " `value` = '{$value}'";
        $query .= " WHERE `parametr` = 'SHOW_FILES_LINK'";
        $result = mysqli_query($link, $query)
        or die("Помилка оновлення налаштувань: " . mysqli_error($link) . "SHOW_FILES_LINK");


        unset($_POST['SAVE_SETTINGS']);
    }
    echo "<header><a href=\"action.php\">Меню</a></header>";
    print_page_settings();

} else Go_page("./index.php");
?>
</body>
</html>