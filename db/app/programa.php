<?php
/**
 * @author Krupnik
 * @copyright 2018
 */
require "./../admin/config.inc.php";
require "./../admin/functions.php";
header("Content-Type: text/html; charset=utf-8");
global $link;
global $settings;
//Прочитать настройки с БД
read_settings();
//Показувати програму конференції тільки при увімненій опції
if ("1" == $settings['SHOW_PROGRAMA']):
/* TODO
Расширити программу конференції
*/
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="../css/print.css" type="text/css" rel="stylesheet">
        <link href="../css/jquery-ui-1.10.3.custom.min.css" type="text/css" rel="stylesheet">
        <title>Реєст &quot;СНР 2018&quot;&copy; / Программа підсумкової конференції</title>
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
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$section = $row['section'];
$i = 1;
$cont_section = 1;
echo "<table class=\"tableprogramma\">\n";
echo "<tr>\n\t<th colspan=\"5\"> Секція №{$cont_section}. Аудиторія {$row['room']} <br>{$row['section']}</th>\n\t</tr>\n";
echo "<tr><th>№</th><th>Доповідь</th><th>Доповідач(i)/<br>Ширф автора</th><th>Керівник</th><th>ВНЗ</th></tr>";
echo "<tr>\n\t<td>{$i}.</td>\n<td>{$row['title']}</td>\n\t";
echo "<td class=\"tda\">";
short_list_leader_or_autors_str($row['id'], "wa", true);
echo "</td>\n\t<td class=\"tdl\">";
short_list_leader_or_autors_str($row['id'], "wl");
echo "</td>\n\t<td class=\"tdu\">{$row['univer']}</td>\n</tr>\n";
$i++;
while ($row = mysqli_fetch_array($result)) {
    if ($row['section'] != $section) {
        $cont_section++;
        echo "<tr>\n\t<th colspan=\"5\"> Секція №{$cont_section}. Аудиторія {$row['room']} <br>{$row['section']}</th>\n\t</tr>\n";
        echo "<tr><th>№</th><th>Доповідь</th><th>Доповідач(i)/<br>Ширф автора</th><th>Керівник</th><th>ВНЗ</th></tr>";
        $i = 1;
    }
    $section = $row['section'];
    echo "<tr>\n\t<td>{$i}.</td><td>{$row['title']}</td>\n\t";
    echo "<td class=\"tda\">";
    short_list_leader_or_autors_str($row['id'], "wa", true);
    echo "</td>\n\t<td class=\"tdl\">";
    short_list_leader_or_autors_str($row['id'], "wl");
    echo "</td>\n\t";
    echo "<td class=\"tdu\">{$row['univer']}</td></tr>\n";
    $i++;//*/
}
echo "</table>";
else:
    Go_page("index.php");
endif;

?>