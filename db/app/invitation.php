<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 20.03.2018
 * Time: 22:22
 */
//header("Content-Type: text/html; charset=utf-8");
require './../admin/config.inc.php';
require './../admin/functions.php';
global $link;
global $settings;
//Прочитать настройки с БД
read_settings();
//Работать если стоит настройка о дотупе и показе приглашений
if ('1' == $settings['INVITATION']):
?>
<!DOCTYPE html>
<html lang="ua">
<?php
//Если не задан номер ВУЗа то отображать страничку выбора
if (!isset($_GET['id_u'])): ?>

    <head>
        <?php include_once 'analyticstracking.php'; ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="../js/jquery.js"></script>
        <link href="../css/userstyle.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="../js/user.js"></script>
        <title>Запрошення &quot;СНР 2018&quot;&copy;</title>
    </head>
    <body>
    <h1>Запрошення для участників конкурсу</h1>
    <p>2-4 квітня 2018 року на поштові адреси ВНЗ направлені листи ректорам з повідомленням про авторів студентських
        наукових робіт, яких запрошено до участі в конференції.
    </p>
    <p>У кожному конверті 3 аркуші:</p>
    <ul>
        <li>лист на офіційному бланку університету зі списком журі;</li>
        <li>додаток 1 зі списком студентів;</li>
        <li>додаток 2 з інформацією про конференцію.</li>
    </ul>
    <p>Електронну копію повідомлення ви можете переглянути та роздрукувати на цій сторінці скориставшись відповідними
        кнопками.</p>
    <br/>
    <?php
    //Список Вузов сформировать
    $query = "select `univers`.`id`, `univers`.`univerfull` \n"
        . "from `univers` \n"
        . "RIGHT JOIN `v_invitation` ON `univers`.`id` = `v_invitation`.`id_u` \n"
        . "WHERE `univers`.`id` <> 1 \n"
        . "ORDER BY `univers`.`univerfull`";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query : " . mysqli_error($link));
    echo "<select id=\"seluniverinv\" size=\"1\" name=\"id_u\" required>\n";
    echo "<option value =\"-1\" disabled>Оберіть ВНЗ та натисніть відповідну клавішу для перегляду документу</option>\n";
    while ($row = mysqli_fetch_array($result)) {
        echo "<option value=\"{$row['id']}\" >{$row['univerfull']}</option>\n";
    }
    echo "</select>\n";
    ?>
    <a href="#" id="letter1link"><input id="letter1button" type="button" value="Лист до ВНЗ"></a>
    <a href="#" id="letter2link"><input id="letter2button" type="button" value="Додаток 1"></a>
    <a href=<?php echo APENDEX2 ?>><input id="letter3button" type="button" value="Додаток 2"></a>
    </body>

<?php
//Если указа номер вуза то показать страничку для печати
else: ?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="../css/print.css" type="text/css" rel="stylesheet"/>
    <title>Запрошення &quot;СНР 2018&quot;&copy;</title>
</head>
<body>

<?php
//Определить номер ВУЗа
$id_u = $_GET['id_u'];

$letter = $_GET['letter'];

//Оффициальное письмо
if ($letter == 1) {
$query = "SELECT v_take_part.id_u AS id_u, v_take_part.count_take_part AS count_take_part,
  v_invitation.count_invitation AS count_invitation, univers.univerrod AS univerrod, univers.rector_r AS rector_r, univers.posada AS posada from `v_take_part` left join v_invitation on v_take_part.id_u = v_invitation.id_u left join univers on v_take_part.id_u = univers.id
where `univers`.`id` <> 1 AND count_invitation > 0 AND v_take_part.id_u='{$id_u}'";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query);
    echo "<div class=\"v_invitation_1\">\n";
    while ($row = mysqli_fetch_array($result)) {
        //var_dump($row['rector_r']);echo "<br>";
        $rector = ($row['rector_r'] != '') ? $row['rector_r'] : "<mark><a href=\"action.php?action=univer_edit&id_u=" . $row['id_u'] . "&FROM={$FROM}\">ЗАПОВНІТЬ ТАБЛИЦЮ</a></mark>";
        $invitatotion = ($row['count_invitation'] != "") ? $row['count_invitation'] : "<mark><a href=\"action.php?action=view#id_u" . $row['id_u'] . "\">ЗАПРОСИТИ?</a></mark>";
        echo "<div id=\"dstuheader\" title=\"Відсканована верхівка офіційного листа\"></div>";
        $blk_rectory = "<div id=\"rectory\">{$row['posada']} "
            . $row['univerrod'] . "<br>"
            . $rector
            . "</div>\n";
        echo $blk_rectory;
        $blk_message = "<div id=\"message\">\n<p>Галузева конкурсна комісія Всеукраїнського конкурсу студентських наукових робіт"
            . " з галузі &quot;Електротехніка та електромеханіка&quot;  запрошує до участі у підсумковій науково-практичній конференції  авторів кращих робіт </p>"
            . "<p>Список запрошених авторів наукових робіт наведено у Додатку 1.</p>\n"
            . "<p>Відповідно до &quot;Положення про  проведення Всеукраїнського конкурсу студентських наукових робіт  з природничих,"
            . " технічних та гуманітарних наук&quot; від {$settings['DATEPO']} №{$settings['ORDERPO']} автор наукової роботи, який  не  брав  участі  у  підсумковій"
            . " науково-практичній конференції,  не може бути претендентом на нагородження.</p>\n"
            . ""
            . "</div>\n";
        echo $blk_message;
        $query2 = "SELECT `leaders`.`invitation`  FROM `leaders` WHERE `id_u` = '" . $row['id_u'] . "' AND `invitation` = TRUE";
        mysqli_query($link, "SET NAMES 'utf8'");
        mysqli_query($link, "SET CHARACTER SET 'utf8'");
        $result2 = mysqli_query($link, $query2)
        or die("Invalid query: " . mysqli_error($link));
        $count = mysqli_num_rows($result2);
        if (0 != $count) {
            echo "<div id=\"message2\"><p>Запрошуємо взяти участь у роботі журі конкурсної комісії конференції представників вашого ВНЗ.</p>";
            list_leaders_invite($row['id_u'], false);
            echo "</div>";
        }
        echo "<div id=\"message2\"><p>Інформація про підсумкову конференцію наведена у Додатку 2.</p>\n</div>";
        /*Сканированая подпись и печать Первого проректора и печать*/
        echo "<div id=\"podpis_image2\" title=\"Сканований підпис Голови комісії\"></div><hr>";
    }
    echo "</div>\n";
} else {
    $query = "SELECT autors.id as autorNumber, CONCAT(autors.suname,' ',autors.name,' ',autors.lname) as fio_a, univers.univerrod as univer, univers.id as id,autors.curse as curse
                FROM autors
                  LEFT JOIN univers ON univers.id=autors.id_u
                  LEFT JOIN wa ON autors.id=wa.id_a
                  LEFT JOIN works ON wa.id_w = works.id
                  WHERE works.invitation = 1 AND univers.id = '{$id_u}'
                  ORDER BY fio_a";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $blk_golova = "<div id=\"podpis\">Заступник голови голови галузевої конкурсної комісії,<br>\nзавідувач кафедри електротехніки та електромеханіки ДДТУ<br><br>\n_______________   В.Б.Нізімов</div>\n";
    $blk_message = "<div id=\"message\">\n<p>"
        . "запрошених на підсумкову науково-практичну конференцію Всеукраїнського конкурсу студентських наукових робіт"
        . " з галузі &quot;Електротехніка та електромеханіка&quot;"
        . "</p>\n"
        . "</div>\n";
    $rowStudent = "<li>%s, (№%s)</li>\n";
    $rowStudentArray = array($row['fio_a'], $row['autorNumber']);
    /* начало формирования списка документов */
    echo "<div class=\"v_invitation_2\">\n";
    /* Пишем первый раз */
    echo "<div id=\"application1\">Додаток 1</div>\n";
    echo "<div id=\"listsudents_title\"><strong>Список студентів</strong></div>\n";
    //Запомним текущий универ чтобы не повторять
    $univer = $row['univer'];
    echo "<div id=\"univer_title\"><em>" . $row['univer'] . "</em></div>\n";
    echo $blk_message;
    echo "<ol>\n";
    vprintf($rowStudent, $rowStudentArray);
    while ($row = mysqli_fetch_array($result)) {
        $rowStudentArray = array($row['fio_a'], $row['autorNumber']);
        if ($univer == $row['univer']) {
            vprintf($rowStudent, $rowStudentArray);
        } else {
            echo "</ol>\n";
            echo "<div id=\"podpis_image\"></div><hr>";
            echo "<div id=\"application1\">Додаток 1</div>\n";
            echo "<div id=\"listsudents_title\"><strong>Список студентів</strong></div>\n";
            $univer = $row['univer'];
            echo "<div id=\"univer_title\"><em>" . $row['univer'] . "</em></div>\n";
            echo $blk_message;
            echo "<ol>\n";
            vprintf($rowStudent, $rowStudentArray);
        }
    }
    echo "</ol>\n";
    /*Сканированная подпись заступника голови*/
    echo "<div id=\"podpis_image4\" title=\"Сканований підпис заступника голови\"></div><hr>";
    echo "</div>\n";
}
endif;
else:Go_page('./');
endif;
?>

</body>
</html>