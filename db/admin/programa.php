<?php

/**
 * @author    study
 * @copyright 2017
 */

require 'config.inc.php';
require 'functions.php';
header("Content-Type: text/html; charset=utf-8");
header("Content-Type: text/html; charset=utf-8");
session_name('tzLogin');
session_start();
global $link;
if (!isset($_SESSION['access']) || $_SESSION['access'] == 0) {
    Go_page("index.php");
}
?>
    <!DOCTYPE html>
    <html lang="ua">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="../css/print.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="../js/jquery.js"></script>
        <script type="text/javascript" src="../js/admin.js"></script>
        <title>Программа підсумкової конференції</title>
    </head>
    <body>
<?php
$query = "SELECT `works`.`id`,`title`,`section`,`univer` 
FROM `works` 
    LEFT OUTER JOIN `sections` ON `id_sec` = `sections`.`id` 
    LEFT OUTER JOIN `univers` ON `works`.`id_u`= `univers`.`id` 
    WHERE `invitation` ='1' 
    ORDER BY `sections`.`id`,`title`;";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$section = $row['section'];
$i = 1;
$cont_section = 1;
echo '<table class="tableprogramma">'
    . '<tr><th colspan="5"> Секція №' . $cont_section . '<br>' . $row['section'] . '</th></tr>'
    . '<tr><th>№</th><th>Доповідь</th><th>Доповідач(i)/<br>Ширф автора</th><th>Керівник</th><th>ВНЗ</th></tr>'
    . "<tr><td>{$i}.</td><td>" . $row['title'] . '</td>' . '<td class="tda">';
short_list_leader_or_autors_str($row['id'], 'wa', true);
echo '</td><td class="tdl">';
short_list_leader_or_autors_str($row['id'], 'wl');
echo '</td><td class="tdu">' . $row['univer'] . '</td></tr>';
$i++;
while ($row = mysqli_fetch_array($result)) {
    if ($row['section'] != $section) {
        $cont_section++;
        echo '<tr><th colspan="5"> Секція №' . $cont_section . '<br>' . $row['section'] . '</th></tr>'
            . '<tr><th>№</th><th>Доповідь</th><th>Доповідач(i)/<br>Ширф автора</th><th>Керівник</th><th>ВНЗ</th></tr>';
        $i = 1;
    }
    $section = $row['section'];
    echo "<tr><td>{$i}.</td><td>" . $row['title'] . '</td>'
        . '<td class="tda">';
    short_list_leader_or_autors_str($row['id'], 'wa', true);
    echo '</td><td class="tdl">';
    short_list_leader_or_autors_str($row['id'], 'wl');
    echo '</td>';
    echo '<td class="tdu">' . $row['univer'] . '</td></tr>';
    $i++;//*/
}
echo '</table>';