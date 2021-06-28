<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:34
 */

use zukr\base\Base;

$db = Base::$app->db;

//Запрос на сбор статистики конкурса
$query = "
SELECT 
(SELECT COUNT(id) AS count FROM works ) 
    as count_works,
(SELECT COUNT(cu) AS count FROM (SELECT COUNT(univer) AS cu FROM works JOIN univers ON works.id_u = univers.id GROUP BY works.id_u) AS tb) 
    as count_univers,
(SELECT count(fio) as count FROM (SELECT CONCAT(suname,' ',name,' ',lname) AS fio FROM wa JOIN works ON wa.id_w = works.id JOIN autors ON autors.id = wa.id_a WHERE autors.arrival = '1' GROUP BY fio) as `table`) 
    as count_authors,
(SELECT COUNT(cu) AS count  FROM (SELECT COUNT(univer) AS cu FROM autors JOIN univers ON autors.id_u = univers.id WHERE autors.arrival='1' GROUP BY autors.id_u) AS tb) 
    as count_univers_from;";
$statData = $db->rawQueryOne($query);

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
$results = $db->rawQuery($query);
$rows = [];
foreach ($results as $row) {
    $rows [] = "<tr><td>Диплом<br/> {$row['place']}-го ступеня</td><td>{$row['autor']}</td><td>{$row['leader']}</td><td>{$row['univerfull']}</td></tr>";
}
?>
<!-- Протокол засідання -->
<header><a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a></header>
<header>Протокол засідання</header>
<?= "<p>Галузева конкурсна комісія забезпечила рецензування 
    {$statData['count_works']}&nbsp;студентських наукових робіт, що надійшли з 
    {$statData['count_univers']} вищих навчальних закладів.</p><p>На підсумковій науково-практичній конференції виступило 
    {$statData['count_authors']}&nbsp;студентів з 
    {$statData['count_univers_from']}&nbsp;вищих навчальних закладів.</p>"
?>
<p>На підставі відкритого обговорення наукових робіт та наукових доповідей учасників науково-практичної конференції
    галузева конкурсна комісія вирішила
    визнати претендентами на нагородження:</p>
<table>
    <tr>
        <th>Диплом І ступеня, ІІ ступеня, ІІІ ступеня (потрібне зазначити)</th>
        <th>Прізвище, ім’я, по батькові студента (повністю)</th>
        <th>Прізвище, ім’я, по батькові (повністю), посада наукового керівника</th>
        <th>Найменування вищого навчального закладу (повністю)</th>
    </tr>
    <?= implode('', $rows) ?>
</table>
<?= getShortListLeadersWhoArrival($db) ?>
<?= getListLeadersWhoArrival($db) ?>
