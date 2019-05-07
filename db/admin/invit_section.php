<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:41
 */
//Выберем только те работы котрые реально существуют
$query = "SELECT works.*, sections.section,univers.univer, COUNT(reviews.id_w) AS countReview
FROM sections, works
  JOIN univers ON works.id_u = univers.id
  JOIN reviews ON reviews.id_w = works.id
WHERE works.id_sec=sections.id AND dead=0 GROUP BY works.id ORDER BY works.balls DESC ";
//echo $query;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query);
$count = mysqli_num_rows($result);

?>
<!-- Разделение по секциям и приглашения -->
<header><a href="action.php">Меню</a></header>
<header>Запрошення (<?= $count ?> всього)</header>
<table id="tableInvitationSection">
    <tr>
        <th>id</th>
        <th>Назва роботи</th>
        <th title="Запросити?">Зв.</th>
        <th>Секція</th
    </tr>
    <?php
    while ($row = mysqli_fetch_array($result)) {
        print_row_table_section_select($row, true);
    }
    ?>
</table>
<h1>Статистика запрошення (F5 - для оновлення)</h1>
<?php
$query = SUPERSQL3;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Помилка запиту: " . mysqli_error($link));
$i = 1;
echo "<table><tr><th>№</th><th>ВНЗ</th><th>Кількість<br>робіт</th><th>Кількість<br>студентів</th><th>Зап.</th></tr>";
while ($row = mysqli_fetch_array($result)) {
    echo "<tr><td>{$i}</td><td>{$row['univerfull']}</td><td>{$row['allworks']}</td><td>{$row['students']}</td><td>{$row['count_invitation']}</td></tr>";
    $i++;
    //print_r($row);
}
echo "</table>";
?>



<!-- Окончание по секциям и приглашения -->
