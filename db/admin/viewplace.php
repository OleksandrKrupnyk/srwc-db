<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:35
 */
printf("<header><a href=\"action.php\">Меню</a></header>\n<header title=\"Розподіл призових місць серед вузів які прийняли участь у конференції\">Розподіл призових місць серед авторів</header>");
printf("<menu class=\"viewTableMenu\"><li><a href=\"action.php?action=setplace\">Редагувати</a></li></menu>");
$query = SUPERSQL; //see file include
//echo $query;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Помилка запиту: " . mysqli_error($link));
$i = 1;
echo "<table>\n<tr><th>№</th><th>ВНЗ</th><th>I</th><th>II</th><th>III</th><th>Диплом(учасника)</th><th>Приїхали</th><th>Запросили</th><th>Подали роботи</th></tr>\n";
while ($row = mysqli_fetch_array($result)) {
    echo "<tr><td>{$i}</td><td>{$row['univer']}</td><td>{$row['first']}</td><td>{$row['second']}</td><td>{$row['third']}</td><td>{$row['diplom']}</td><td>{$row['conf']}</td><td>{$row['count_invitation']}</td><td>{$row['count_takepart']}</td></tr>";
    $i++;
    //print_r($row);
}
$query = "SELECT CONCAT(autors.suname,' ',autors.name,' ',autors.lname) 
              AS fio FROM autors GROUP BY fio";
$result = mysqli_query($link, $query)
or die("Помилка запиту на отримання загальної кількості авторів наукових робіт: " . mysqli_error($link));
$count1 = mysqli_num_rows($result);
$count2 = round($count1 * 0.25);

$query = SUPERSQL2; //see file include
$result = mysqli_query($link, $query)
or die("Помилка запиту: " . mysqli_error($link));
$row = mysqli_fetch_array($result);
echo "<tr><th colspan=\"2\">РАЗОМ<br>авторів</th><th>{$row['first']}<br>".round($count2 * 0.20)."</th><th>{$row['second']}<br>".round($count2 * 0.30)."</th><th>{$row['third']}<br>".($count2 - round($count2 * 0.20) - round($count2 * 0.30))."</th><th>{$row['diplom']}</th><th>{$row['conf']}</th><th>{$row['count_invitation']}</th><th>{$row['count_takepart']}</th></tr>";
echo "</table>";

echo "<strong>Всьго студентів авторів</strong>: {$count1}. Нагородити 25% від загальної кількості авторів наукових робіт (Р.VI п.1 Положення про конкурс) це складає <strong>{$count2}</strong>. Дипломами 1-го ступеня <strong>" . round($count2 * 0.20)
    . "</strong>, дипломами 2-го ступеня <strong>" . round($count2 * 0.30) . "</strong>, дипломами 3-го ступеня <strong>" . ($count2 - round($count2 * 0.20) - round($count2 * 0.30)) . "</strong>";
echo "<p><a href=\"http://zakon.rada.gov.ua/laws/show/z0620-17\">Дивитись Положення про конкурс </a></p>";
?>

<!-- Окончание просмотра результата распределения мест -->

