<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:34
 */
//Запрос на сбор статистики конкурса
global $link;
$query = "SELECT COUNT(id) AS count FROM works
                UNION
                SELECT COUNT(cu) AS count
                      FROM (SELECT COUNT(univer) AS cu
                            FROM works
                              JOIN univers ON works.id_u = univers.id
                            GROUP BY works.id_u) AS tb
                UNION
                SELECT count(fio) as count
                FROM (SELECT CONCAT(suname,' ',name,' ',lname) AS fio
                      FROM wa
                        JOIN works ON wa.id_w = works.id
                        JOIN autors ON autors.id = wa.id_a
                      WHERE autors.arrival = '1'
                      GROUP BY fio) as `table`
                UNION
                SELECT COUNT(cu) AS count
                FROM (SELECT COUNT(univer) AS cu
                      FROM autors JOIN univers ON autors.id_u = univers.id
                      WHERE autors.arrival='1' GROUP BY autors.id_u) AS tb";
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Помилка запиту: " . mysqli_error($link));
$row = mysqli_fetch_array($result);
$count_works = $row['count'];
$row = mysqli_fetch_array($result);
$count_univers = $row['count'];
$row = mysqli_fetch_array($result);
$count_students = $row['count'];
$row = mysqli_fetch_array($result);
$count_univers_from = $row['count'];

$txt = "<p>Галузева конкурсна комісія забезпечила рецензування 
    {$count_works}&nbsp;студентських наукових робіт, що надійшли з 
    {$count_univers} вищих навчальних закладів.</p><p>На підсумковій науково-практичній конференції виступило 
    {$count_students}&nbsp;студентів з 
    {$count_univers_from}&nbsp;вищих навчальних закладів.</p>";
$txt .= "<p>На підставі відкритого обговорення наукових робіт та наукових доповідей учасників науково-практичної конференції галузева конкурсна комісія вирішила
визнати претендентами на нагородження:</p>";
$query = "SELECT autors.place,
                  CONCAT(autors.suname,'<br>',autors.name,'<br>',autors.lname) AS autor,
                  CONCAT(leaders.suname,'<br>',leaders.name,'<br>',leaders.lname,',<br>',positions.position) AS leader,
                  univers.univerfull
                FROM autors
                  JOIN univers ON autors.id_u = univers.id
                  JOIN wa ON autors.id = wa.id_a
                  JOIN wl ON wa.id_w = wl.id_w
                  JOIN leaders ON wl.id_l = leaders.id
                  JOIN positions ON leaders.id_pos = positions.id
                  WHERE autors.place <> 'D' AND autors.arrival = '1'
                ORDER BY autors.place";
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Помилка запиту: " . mysqli_error($link));


?>
<!-- Протокол засідання -->
<header><a href="action.php">Меню</a></header>
<header>Протокол засідання</header>
<?= $txt?>

<table>
    <tr>
        <th>Диплом І ступеня, ІІ ступеня, ІІІ ступеня (потрібне зазначити)</th>
        <th>Прізвище, ім’я, по батькові студента (повністю)</th>
        <th>Прізвище, ім’я, по батькові (повністю), посада наукового керівника </th>
        <th>Найменування вищого навчального закладу (повністю)</th>
    </tr>
<?php
while ($row = mysqli_fetch_array($result)) {
    echo "<tr><td>Диплом<br/> {$row['place']}-го ступеня</td><td>{$row['autor']}</td><td>{$row['leader']}</td><td>{$row['univerfull']}</td></tr>";
}
?>
</table>
<?php
listLeadersWhoArrival();
listLeadersWhoArrival(true);
?>
<!-- Окончание  Протокол засідання-->
