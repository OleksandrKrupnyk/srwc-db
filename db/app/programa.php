<?php
/**
 * @author    Krupnik
 * @copyright 2018
 */


require './../admin/config.inc.php';
require './../admin/functions.php';
require '../vendor/autoload.php';

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;

header('Content-Type: text/html; charset=utf-8');
Base::init();
$settings = ArrayHelper::merge($settings, Base::$param->getAllsettingValue());
global $link;
if ('1' === $settings['SHOW_PROGRAMA']): ?>
    <!DOCTYPE html>
    <html lang="ua">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="../css/print.css" type="text/css" rel="stylesheet">
        <title>Реєст <?= Base::$app->app_name ?> / Программа підсумкової конференції</title>
    </head>
    <body class="programa">
    <h1>Программа підсумкової науково-практичної конференції</h1>
    <h1>Всеукраїнського конкурсу студентських наукових робіт</h1>
    <h1>з галузі</h1>
    <h1>&quot;Електротехніка та електромеханіка&quot;</h1>
    <?php
    $query = "SELECT works.id, title, section,univer, sections.room 
        FROM works 
        LEFT OUTER JOIN sections ON id_sec = sections.id 
        LEFT OUTER JOIN univers ON works.id_u= univers.id\n
        WHERE invitation ='1'
        ORDER BY sections.id,title";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $section = $row['section'];
    $i = 1;
    $cont_section = 1;
    echo '<table class="tableprogramma">'
        . "<tr><th colspan=\"5\"> Секція №{$cont_section}. Аудиторія {$row['room']} <br>{$row['section']}</th></tr>"
        . '<tr><th>№</th><th>Доповідь</th><th>Доповідач(i)/<br>Ширф автора</th><th>Керівник</th><th>ВНЗ</th></tr>'
        . "<tr><td>{$i}.</td><td>{$row['title']}</td>";
    echo '<td class="tda">';
    short_list_leader_or_autors_str($row['id'], "wa", true);
    echo '</td><td class="tdl">';
    short_list_leader_or_autors_str($row['id'], "wl");
    echo "</td><td class=\"tdu\">{$row['univer']}</td></tr>";
    $i++;
    while ($row = mysqli_fetch_array($result)) {
        if ($row['section'] != $section) {
            $cont_section++;
            echo "<tr><th colspan=\"5\"> Секція №{$cont_section}. Аудиторія {$row['room']} <br>{$row['section']}</th></tr>";
            echo '<tr><th>№</th><th>Доповідь</th><th>Доповідач(i)/<br>Ширф автора</th><th>Керівник</th><th>ВНЗ</th></tr>';
            $i = 1;
        }
        $section = $row['section'];
        echo "<tr><td>{$i}.</td><td>{$row['title']}</td>" .
            '<td class="tda">';
        short_list_leader_or_autors_str($row['id'], 'wa', true);
        echo '</td><td class="tdl">';
        short_list_leader_or_autors_str($row['id'], 'wl');
        echo '</td>';
        echo "<td class=\"tdu\">{$row['univer']}</td></tr>";
        $i++;
    }
    echo "</table>";
else:
    Go_page('index.php');
endif;