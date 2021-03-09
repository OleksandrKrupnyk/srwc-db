<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:32
 */

printf("<header><a href=\"action.php\">Меню</a></header>\n<header>Тестова сторінка</header>");
$query = "
     SELECT works.title,  CONCAT(autors.suname,' ',autors.name,' ',autors.lname) AS fio, autors.email ,univers.univer 
     FROM works  
     LEFT JOIN wa ON works.id = wa.id_w 
     LEFT JOIN autors ON wa.id_a = autors.id
     LEFT JOIN univers ON works.id_u = univers.id
     WHERE invitation = '1' ORDER BY univers.univer, fio ASC";
//echo "<pre>{$query}</pre>";
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Помилка запиту: " . mysqli_error($link));
//echo "<pre>";
$row = mysqli_fetch_array($result);
$fio = $row['fio'];
$count_fio = 0; //счетчик фамилий
$count_title = 0; //счетчик работ
echo "<form class='form' method='post' action='test.php'>";
echo "<ol>";
$row['email'] = ($row['email'] == "") ? "<mark>Пошта відсутня!</mark>" : $row['email'];
echo "<li>" . $row['fio'] . " " . $row['email'];
echo "<input type='hidden' name=\"fios[{$count_fio}]\" value=\"{$row['fio']}\">";
echo "<input type='hidden' name=\"emails[]\" value=\"{$row['email']}\">";
echo "<ul>";
echo "<li>{$row['title']}<input type='hidden' name=\"works[{$count_fio}][{$count_title}]\" value=\"{$row['title']}\"></li>";
$count_fio++; // увеличить счетчик фамилий
$count_title++; // увеличить счетчик работ
while ($row = mysqli_fetch_array($result)) {
    //print_r($row);
    if ($fio <> $row['fio']) { // сменился автор
        echo "</ul></li>"; //закрыть список
        $fio = $row['fio']; // запомнить нового автора
        $count_fio++; // увеличить счетчик авторов
        $count_title = 0; // сбросить счетчик работ в ноль
        $row['email'] = ($row['email'] == "") ? "<mark>Пошта відсутня!</mark>" : $row['email'];
        echo "<li>" . $row['fio'] . " " . $row['email'];
        echo "<input type='hidden' name=\"fios[{$count_fio}]\" value=\"{$row['fio']}\">";
        echo "<input type='hidden' name=\"emails[]\" value=\"{$row['email']}\">";
        echo "<ul>"; // начать запись работ
        echo "<li>{$row['title']}<input type='hidden' name=\"works[{$count_fio}][{$count_title}]\" value=\"{$row['title']}\"></li>";
    } else { // иначе есть еще одна работа у автора
        $count_title++; // увеличить счетчик работ на 1
        echo "<li>{$row['title']}<input type='hidden' name=\"works[{$count_fio}][{$count_title}]\" value=\"{$row['title']}\"></li>"; //дописать еще одну работу
    }
}
//echo "</pre>";
echo "</ol>";
echo "<input type='submit' value='ПРОВЕРКА'>";
echo "</form>";
?>
<!-- Список на отправку Тестовая разработка -->
