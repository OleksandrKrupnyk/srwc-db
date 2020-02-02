<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:41
 */
include __DIR__ . '/../sqlquery.inc.php';

use zukr\base\html\Html;
use zukr\base\html\HtmlHelper;
use zukr\section\SectionHelper;
use zukr\work\WorkHelper;
use zukr\work\WorkRepository;

$wh = WorkHelper::getInstance();
//Выберем только те работы котрые реально существуют
$works = (new WorkRepository())->getWorksForInvitationAndSection();
$sections = SectionHelper::getInstance()->getDropdownList();
?>
<!--Запрошення робіт-->
<!-- Разделение по секциям и приглашения -->
<header><a href="action.php">Меню</a></header>
<header>Запрошення (<?= count($works) ?> всього)</header>
<table id="tableInvitationSection" class="zebra">
    <tr>
        <th>id</th>
        <th>Назва роботи</th>
        <th title="Запросити?">Зв.</th>
        <th>Секція</th
    </tr>
    <?php
    foreach ($works as $w) {
        echo '<tr data-key="'.$w['id'].'">
<td><a href="action.php?action=work_edit&id_w=' . $w['id'] . '" title="Редагувати роботу">' . $w['id'] . '</a></td>
<td>' . $w['title'] . ' (' . $w['univer'] . ')<strong>[' . $w['balls'] . ']</strong><' . $w['countReview'] . '></td>
<td>'. HtmlHelper::checkboxStyled('invitation', 'Відмітити для запрошення', $w['invitation']).'</td>
<td>' . Html::select('sections', $w['id_sec'], $sections) . '</td>
</tr>';
    }?>
</table>
<h1>Статистика запрошення (F5 - для оновлення)</h1>
<?php
global $link;
$query = SUPERSQL3;
$result = mysqli_query($link, $query)
or die('Помилка запиту: ' . mysqli_error($link));
$i = 1;
echo '<table><tr><th>№</th><th>ВНЗ</th><th>Кількість<br>робіт</th><th>Кількість<br>студентів</th><th>Зап.</th></tr>';
while ($row = mysqli_fetch_array($result)) {
    echo "<tr><td>{$i}</td><td>{$row['univerfull']}</td><td>{$row['allworks']}</td><td>{$row['students']}</td><td>{$row['count_invitation']}</td></tr>";
    $i++;
}
echo "</table>";
echo $wh->registerJS('work.js');
?>
<!-- Окончание по секциям и приглашения -->
