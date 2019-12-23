<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:33
 */
include __DIR__ . '/../sqlquery.inc.php';
global $link;
$query = "SELECT COUNT(id) AS count  FROM works ";
$query .= " UNION SELECT COUNT(ca) AS count FROM (SELECT COUNT(id_a) AS ca FROM wa JOIN works ON works.id = wa.id_w GROUP BY id_a ) AS tb";
$query .= " UNION SELECT COUNT(cl) AS count FROM (SELECT COUNT(id_l) AS cl FROM wl JOIN works ON works.id = wl.id_w GROUP BY id_l ) AS tb ";
$query .= " UNION SELECT COUNT(cu) AS count FROM (SELECT COUNT(univer) AS cu FROM works JOIN univers ON works.id_u = univers.id GROUP BY works.id_u) AS tb";
$result = mysqli_query($link, $query)
or die("Помилка запиту на отримання статистики: " . mysqli_error($link));
$row = mysqli_fetch_array($result);
$count_works = $row['count'];
$row = mysqli_fetch_array($result);
$count_autors = $row['count'];
$row = mysqli_fetch_array($result);
$count_leaders = $row['count'];
$row = mysqli_fetch_array($result);
$count_univers_from = $row['count'];
$txt = "<p>На Конкурс 2017/2018&nbsp;н.р. з галузі “Електротехніка та електромеханіка” надійшло ";
$txt .= works_declension($count_works) . " ( {$count_autors} студентів-авторів, {$count_leaders} науковий керівників) з {$count_univers_from} вищих навчальних закладів.</p>";

$query = SUPERSQL3;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Помилка запиту: " . mysqli_error($link));

?>
<!-- Статистична довідка -->
<header><a href='action.php'>Меню</a></header>
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
    <?php
    $i = 1;
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr><td>{$i}</td><td>{$row['univerfull']}</td>
<td>{$row['allworks']}</td>
<td>{$row['students']}</td>
<td>{$row['first']}</td>
<td>{$row['second']}</td>
<td>{$row['third']}</td><td>{$row['count_invitation']}</td></tr>";
        $i++;
    }
    ?>
</table>
<p>Інформація роздрукована з сайту http://elm-dstu-edu.org.ua/db/</p>
