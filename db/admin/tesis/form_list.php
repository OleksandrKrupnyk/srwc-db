<?php
/**
 * Список тез для формування збірника
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:39
 */
printf("<header><a href=\"action.php\">Меню</a></header>\n<header>Тезиси</header>");
//формируем запрос на получение данных
$query = "SELECT works.title,works.id,sections.section,univers.town FROM works 
LEFT JOIN sections ON works.id_sec=sections.id 
LEFT JOIN univers ON works.id_u=univers.id
WHERE tesis = 1 GROUP BY id_sec,title";
//настройка соединения
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
//посылаем запрос
$result = mysqli_query($link, $query);
//первый запрос
$row = mysqli_fetch_array($result);
//Запомним секцию
$section = $row['section'];
echo "<div id=\"sectiontitle\">" . $row['section'] . "</div>";
short_list_leader_or_autors_str($row['id'], "wa");
echo(list_files($row['id'], 1));
echo TAB_SP . "(" . $row['title'] . ")" . TAB_SP . "<strong><em>" . $row['town'] . "</em></strong><br>"; // Напишем название работы
//print_r($row);
//Начнем перебор
while ($row = mysqli_fetch_array($result)) {
    //Поменялась ли секция
    if ($section == $row['section']) {//нет не поменялась
        short_list_leader_or_autors_str($row['id'], "wa");
        echo(list_files($row['id'], 1));
        echo TAB_SP . "(" . $row['title'] . ")" . TAB_SP . "<strong><em>" . $row['town'] . "</em></strong><br>"; // Напишем название работы
    } else {//Секциия поменялась
        //Запомним новую секцию
        $section = $row['section'];
        //Напишем название секции
        echo "<div id=\"sectiontitle\">" . $row['section'] . "</div>";
        //Запишем первую работу из новой секции
        short_list_leader_or_autors_str($row['id'], "wa");
        /*Вставить список файлов работы*/
        echo(list_files($row['id'], 1));
        echo TAB_SP . "(" . $row['title'] . ")" . TAB_SP . "<strong><em>" . $row['town'] . "</em></strong><br>"; // Напишем название работы

    }
}
?>
<!-- Окончание списока тезисов по секциям -->
