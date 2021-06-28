<?php
/**
 * Список тез для формування збірника
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:39
 */

//формируем запрос на получение данных
use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;

$db = Base::$app->db;
$query = "SELECT works.title,works.id,sections.section,univers.town FROM works 
LEFT JOIN sections ON works.id_sec=sections.id 
LEFT JOIN univers ON works.id_u=univers.id
WHERE tesis = 1 GROUP BY id_sec,title";
//посылаем запрос
$results = $db->rawQuery($query);
$results = ArrayHelper::group($results, 'section');
?>
    <!-- Список тезисов по секциям -->
    <style>
        .sectiontitle {
            text-align: center;
            font-weight: bold;
        }
    </style>
    <header><a href='action.php'><i class="icofont-navigation-menu"></i> Меню</a></header>
    <header>Тезиси</header>
<?php
foreach ($results as $section => $result) {
    echo "<div id='sectiontitle' class='sectiontitle'>{$section}</div>";
    foreach ($result as $row) {
        //Запишем первую работу из новой секции
        echo getShortListAutors($db, $row['id'])
        /*Вставить список файлов работы*/
        .list_files($row['id'], $db, 1)
        . TAB_SP . "(" . $row['title'] . ")" . TAB_SP . "<strong><em>" . $row['town'] . "</em></strong><br>"; // Напишем название работы
    }
}
