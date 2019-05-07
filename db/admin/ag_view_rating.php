<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 18.11.17
 * Time: 22:18
 */
if ("1" == $settings['SHOW_RAITING']) {
$query = "SELECT * FROM  `works` ORDER BY  `works`.`balls` DESC";
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Invalid query in function ag_view_raiting : " . mysqli_error($link));
$str = "<header><a href=\"index.php\">Головна</a></header><header>Рейтинг</header><table><tr><th>Бали</th><th>Шифр</th><th>Робота</th></tr>";?>
<?php
while ($row = mysqli_fetch_array($result)) {
    $invitationClass = ($row['invitation'] == 1) ? "class=\"invitateWork\" title=\"Автори запрошуються до участі у конференції\"" : "";
    $str .= "<tr {$invitationClass}><td>{$row['balls']}</td><td>{$row['motto']}</td><td>{$row['title']}</td></tr>\n";
}
$str .= "</table>";
echo  $str;
} else {Go_page("./index.php");}
?>