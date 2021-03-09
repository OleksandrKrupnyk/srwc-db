<?php

/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:37
 */

use zukr\base\helpers\StringHelper;
use zukr\base\html\Html;
use zukr\place\PlaceHelper;

global $link;
$query = "SELECT a.id, 
       CONCAT(a.suname,' ',a.name,' ',a.lname) AS fio, 
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
$result = mysqli_query($link, $query)
or die("Помилка запиту: " . mysqli_error($link));

$row = mysqli_fetch_array($result);

$title = StringHelper::truncate($row['title'], 30);
$section = $row['section'];
$ph = PlaceHelper::getInstance();
?>
    <!-- Распределение мест среди студентов которые приехали на конференцию-->
    <header><a href='action.php'>Меню</a></header>
    <header>Призначення місць.</header>
    Сума місць[<span id='summaryResult'></span>]
    <menu class='viewTableMenu'>
        <li><a href='action.php?action=place_view'>Деталі</a></li>
    </menu>
    <table id='tableSetPlace'>
        <tr>
            <th>ID учас.</th>
            <th>Учасник/Автор</th>
            <th>ВНЗ</th>
            <th>Місце</th>
            <th>(шифр) &sum;реценз.; Робота</th>
        </tr>
        <?php
        echo "<tr><th colspan='5'>{$section}</th></tr>
          <tr data-key='{$row['id']}'><td>{$row['id']}</td><td>{$row['fio']}</td><td>{$row['univer']}</td><td>"
            . Html::select('place', $row['place'], $ph->getPlaceList(), ['title' => "Призове місце:(D-Диплом за участь)"])
            . "</td><td title=\"{$row['title']}\">({$row['id_w']}) &sum;{$row['balls']} ; {$title}</td></tr>";
        while ($row = mysqli_fetch_array($result)) {
            if ($section !== $row['section']) {
                $section = $row['section'];
                echo "<tr><th colspan='5'>{$section}</th></tr>";
            }
            $title = mb_substr($row['title'], 0, 30, "utf-8") . "...";
            echo "<tr data-key='{$row['id']}'><td>{$row['id']}</td><td>{$row['fio']}</td><td>{$row['univer']}</td><td>"
                . Html::select('place', $row['place'], $ph->getPlaceList(), ['title' => "Призове місце:(D-Диплом за участь)"])
                . "</td><td title='{$row['title']}'>({$row['id_w']}) &sum;{$row['balls']} ; {$title}</td></tr>";
        } ?>
    </table>
    <!-- Распределение мест стреди студентов которые приехали на конференцию-->
<?= $ph->registerJS('place.js') ?>