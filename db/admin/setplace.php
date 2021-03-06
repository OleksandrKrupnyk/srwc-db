<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:37
 */

$query = "SELECT a.id, CONCAT(a.suname,' ',a.name,' ',a.lname) AS fio, 
       univers.univer,
       a.place,
       works.id AS id_w,
       works.balls,
       works.title, 
       sections.section 
FROM `autors` AS a
  JOIN wa ON wa.id_a = a.id
  JOIN `works` ON wa.id_w = works.id
  JOIN `sections` ON works.id_sec = sections.id
  JOIN `univers` ON a.id_u = univers.id
  WHERE a.arrival = '1' 
    AND works.invitation = '1' 
ORDER BY sections.id, a.id;";
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Помилка запиту: " . mysqli_error($link));

$row = mysqli_fetch_array($result);
$title = mb_substr($row['title'],0,30,"utf-8")."...";
$section = $row['section'];


echo "<header><a href='action.php'>Меню</a></header>\n";
echo "<header>Призначення місць.</header>";
echo "Сума місць[<span id='summaryResult'></span>]\n";
echo "<menu class='viewTableMenu'>\n<li><a href='action.php?action=viewplace'>Деталі</a></li>\n</menu>\n";
echo "<table id='tableSetPlace'>\n";
echo "<tr><th>ID учас.</th><th>Учасник/Автор</th><th>ВНЗ</th><th>Місце</th><th>(шифр) &sum;реценз.; Робота</th></tr>\n";
echo "<tr><th colspan='5'>{$section}</th></tr>\n";
echo "<tr><td>{$row['id']}</td><td>{$row['fio']}</td><td>{$row['univer']}</td><td>";
cbo_place($row['place']);
echo "</td><td title=\"{$row['title']}\">({$row['id_w']}) &sum;{$row['balls']} ; {$title}</td></tr>";
while ($row = mysqli_fetch_array($result)) {
    if ($section != $row['section']) {
        $section = $row['section'];
        echo "<tr><th colspan=\"5\">{$section}</th></tr>";
    }
    $title = mb_substr($row['title'],0,30,"utf-8")."...";
    echo "<tr><td>{$row['id']}</td><td>{$row['fio']}</td><td>{$row['univer']}</td><td>\n";
    cbo_place($row['place']);
    echo "</td><td title=\"{$row['title']}\">({$row['id_w']}) &sum;{$row['balls']} ; {$title}</td></tr>";
}
echo "</table>"; ?>
<!-- Распределение мест стреди студентов которые приехали на конференцию-->
