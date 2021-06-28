<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:33
 */
include __DIR__ . '/../sqlquery.inc.php';

use zukr\base\Base;
use zukr\base\helpers\TextHelper;

$db = Base::$app->db;
$settings = Base::$param;
$query = "SELECT 
(SELECT COUNT(id) AS count  FROM works) 
    as count_works, 
(SELECT COUNT(ca) AS count FROM (SELECT COUNT(id_a) AS ca FROM wa JOIN works ON works.id = wa.id_w GROUP BY id_a ) AS tb) 
    as count_autors,
(SELECT COUNT(cl) AS count FROM (SELECT COUNT(id_l) AS cl FROM wl JOIN works ON works.id = wl.id_w GROUP BY id_l ) AS tb ) 
    as count_leaders,
(SELECT COUNT(cu) AS count FROM (SELECT COUNT(univer) AS cu FROM works JOIN univers ON works.id_u = univers.id GROUP BY works.id_u) AS tb) 
as count_univers_from; 
";
$statData = $db->rawQueryOne($query);

$count_works = $statData['count_works'];
$count_autors = $statData['count_autors'];
$count_leaders = $statData['count_leaders'];
$count_univers_from = $statData['count_univers_from'];
$txt = "
<p>На Конкурс $settings->NYEARS&nbsp;н.р. з галузі “Електротехніка та електромеханіка” надійшло "
    . TextHelper::declensionWork($count_works) . " ( $count_autors студентів-авторів, $count_leaders науковий керівників) з $count_univers_from вищих навчальних закладів.
</p>";

$query = SUPERSQL3;

$results = $db->rawQuery($query);
$rows = [];
foreach ($results as $i => $row) {
    $rows [] = "<tr><td>" . ($i + 1) . "</td><td>{$row['univerfull']}</td>
<td>{$row['allworks']}</td>
<td>{$row['students']}</td>
<td>{$row['first']}</td>
<td>{$row['second']}</td>
<td>{$row['third']}</td><td>{$row['count_invitation']}</td></tr>";
}
?>
<!-- Статистична довідка -->
<header><a href='action.php'><i class="icofont-navigation-menu"></i> Меню</a></header>
<header>Статистична довідка</header>
<?= $txt ?>
<table>
    <tr>
        <th>№</th>
        <th>ВНЗ</th>
        <th>Кількість<br>робіт
        </th
        >
        <th>Кількість<br>студентів</th>
        <th>I</th>
        <th>II</th>
        <th>III</th>
        <th>Зап.</th>
    </tr>
    <?= implode('', $rows)
    ?>
</table>
<p>Інформація роздрукована з сайту http://elm-dstu-edu.org.ua/db/</p>
