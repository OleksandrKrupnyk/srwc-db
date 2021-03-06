<?php
/**
 * @author studyjquery
 * @copyright 2015
 */
require 'config.inc.php';
require 'functions.php';
header("Content-Type: text/html; charset=utf-8");
session_name('tzLogin');
session_start();
global $link;
global $FROM;
//var_dump($_POST);
//var_dump($_GET);
//Если есть доступ к странице
?>
<!DOCTYPE html>
<html lang="ua">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="../css/print.css" type="text/css" rel="stylesheet">
    <link href="../css/jquery-ui-1.10.3.custom.min.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-1.10.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>
    <title>ДРУКУВАТИ &quot;СНР 2018&quot;&copy;</title>
</head>
<body>
<?php //переменная для определения предка вызова сценария
$FROM = trim(urlencode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
?>
<?php
// Прочитать насторйки программы из БД
read_settings();

if ($_SESSION['access']) {
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    switch (filter_input(INPUT_GET, 'list')) {
        case "adress":
            {
                $query = "SELECT univers.univerfull, univers.adress, univers.zipcode 
                      FROM univers WHERE univers.invite = '1' GROUP BY univerfull ASC";
                $result = mysqli_query($link, $query);
                echo "<div class=\"adress2\">\n";
                echo "<header>Список розсилки 1-го інформаційного повідомлення <br>Всеукраїнського конкурсу студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</header>\n";
                //echo $query;
                echo "<div id=\"table\"><table>\n";
                echo "<tr><th>№</th><th>Кому</th><th>Адреса</th></tr>\n";
                $i = 1;
                while ($row = mysqli_fetch_array($result)) {

                    print_row_table_list_adress2($i, $row);
                    $i++;
                    if (($i == 23) || ($i == 48)) {
                        echo "</table>\n";
                        echo "</div>\n";
                        echo "<div class = \"tableprotocol\"></div>\n";
                        echo "<div class=\"adress2\">\n";
                        echo "<div id=\"table\"><table>\n";
                        echo "<tr><th>№</th><th>Кому</th><th>Адреса</th></tr>\n";
                    }
                }
                echo "</table>\n";
                echo "</div>\n";
                echo "<div id=\"prorector\">Перший проректор ДДТУ<br></div>\n";
                echo "<div id=\"prorectorNAME\">В.М. Гуляєв</div>\n";
                echo "</div>";
            }
            break;
        case "adress2":
            {//ТОлько те работы у коротых есть приглашенные работы минус ДДТУ
                $query = "SELECT univers.* 
                      FROM univers
                      LEFT JOIN works ON univers.id = works.id_u 
                      WHERE works.invitation = '1' AND univers.id != '1' 
                      GROUP BY univers.univer 
                      ORDER BY univers.univer ASC";
                $result = mysqli_query($link, $query);
                echo "<div class=\"adress2\">\n";
                echo "<header>Список розсилки 2-го інформаційного повідомлення <br>Всеукраїнського конкурсу студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</header>\n";
                //echo $query;
                echo "<div id=\"table\"><table>\n";
                echo "<tr><th>№</th><th>Кому</th><th>Адреса</th></tr>\n";
                $i = 1;
                while ($row = mysqli_fetch_array($result)) {

                    print_row_table_list_adress2($i, $row);
                    $i++;
                    if (($i == 21) || ($i == 47)) {
                        echo "</table>\n</div>\n";
                        echo "<div class = \"tableprotocol\"></div>\n";
                        echo "<div class=\"adress2\">\n";
                        echo "<div id=\"table\"><table>\n";
                        echo "<tr><th>№</th><th>Кому</th><th>Адреса</th></tr>\n";
                    }
                }
                echo "</table>\n";
                echo "</div>\n";
                echo "<div id=\"prorector\">Перший проректор ДДТУ<br></div>\n";
                echo "<div id=\"prorectorNAME\">В.М. Гуляєв</div>\n";
                echo "</div>";
            }
            break;
        case "envelope"://Конерти 1-го інфомаційного повідомлення
            {
                $query = "SELECT univers.univerfull,univers.adress,univers.zipcode FROM univers WHERE univers.invite = '1' GROUP BY univerfull ASC";
                $result = mysqli_query($link, $query);
                echo "<div class=\"envelope\">\n";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<div id=\"fromAdress\"><strong><ins>Всеукраїнський конкурс студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</ins></strong><br>\n"
                        . "<em>вул.&nbsp;Дніпробудівська,2 м.&nbsp;Кам’янське,<br>\nДніпропетровська обл.</em><br>\n"
                        . "<strong>51918</strong></div>";
                    echo "<div id=\"whomAdress\">";
                    print_adress2($row);
                    echo "</div><hr>\n";
                }
                echo "</div>";
            }
            break;
        case "envelope2"://Конерти 2-го інфомаційного повідомлення
            {
                $query = "select univers.* FROM univers LEFT JOIN works ON univers.id = works.id_u WHERE works.invitation = '1' AND univers.id != '1' GROUP BY univers.univer ORDER BY univers.univer ASC";
                $result = mysqli_query($link, $query);
                echo "<div class=\"envelope\">\n";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<div id=\"fromAdress\"><strong><ins>Всеукраїнський конкурс студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</ins></strong><br>\n"
                        . "<em>вул.&nbsp;Дніпробудівська,2 м.&nbsp;Кам’янське,<br>\nДніпропетровська обл.</em><br>\n"
                        . "<strong>51918</strong></div>";
                    echo "<div id=\"whomAdress\">";
                    print_adress2($row);
                    echo "</div><hr>\n";
                }
                echo "</div>";
            }
            break;
        case "invitation_1"://Запрошення
            {
            $query = "SELECT v_take_part.id_u AS id_u, v_take_part.count_take_part AS count_take_part,
  v_invitation.count_invitation AS count_invitation, univers.univerrod AS univerrod, univers.rector_r AS rector_r, univers.posada AS posada from `v_take_part` left join v_invitation on v_take_part.id_u = v_invitation.id_u left join univers on v_take_part.id_u = univers.id
where (univers.id <> '1') AND (count_invitation > 0) ORDER BY univerfull ASC ";
            //echo $query;
                $result = mysqli_query($link, $query);
                $total = mysqli_num_rows($result);
                if ($total <> 0) {//Обрабатывем если хоть какието строки получены
                    echo "<div class=\"v_invitation_1\">\n";
                    while ($row = mysqli_fetch_array($result)) {
                        //var_dump($row['rector_r']);echo "<br>";
                        $rector = ($row['rector_r'] != "") ? $row['rector_r'] : "<mark><a href=\"action.php?action=univer_edit&id_u={$row['id_u']}&FROM={$FROM}\">ЗАПОВНІТЬ ДАНІ ПРО ВНЗ</a></mark>";
                        $invitation = ($row['count_invitation'] != "") ? $row['count_invitation'] : "<mark><a href=\"action.php?action=view#id_u{$row['id_u']}\">ЗАПРОСИТИ?</a></mark>";
                        $invitation = "";
                        // Печатать Шапку университета
                        PrintGerb($empty = true);//Печатет данные бланка Герб и т.д.
                        $blk_rectory = "<div id=\"rectory\">{$row['posada']} {$row['univerrod']}<br>\n{$rector}</div>\n";
                        echo $blk_rectory;
                        $blk_message = "<div id=\"message\">\n\t<p>Галузева конкурсна комісія Всеукраїнського конкурсу студентських наукових робіт"
                            . " з галузі &quot;Електротехніка та електромеханіка&quot; запрошує до участі у підсумковій науково-практичній конференції авторів кращих робіт. </p>"
                            . "\n\t<p>Список запрошених авторів наукових робіт наведено у Додатку 1.</p>"
                            . "\n\t<p>Відповідно до &quot;Положення про  проведення Всеукраїнського конкурсу студентських наукових робіт  з природничих,"
                            . " технічних та гуманітарних наук&quot; від {$settings['DATEPO']} №{$settings['ORDERPO']} автор наукової роботи, який  не  брав  участі  у  підсумковій"
                            . " науково-практичній конференції, не може бути претендентом на нагородження.</p>\n"
                            . "</div>\n";
                        echo $blk_message;
                        $query2 = "SELECT leaders.invitation  FROM leaders WHERE id_u = '{$row['id_u']}' AND invitation = TRUE";
                        mysqli_query($link, "SET NAMES 'utf8'");
                        mysqli_query($link, "SET CHARACTER SET 'utf8'");
                        $result2 = mysqli_query($link, $query2)
                        or die("Invalid query: " . mysqli_error($link));
                        $count = mysqli_num_rows($result2);
                        // Список журі від ВНЗ
                        if (0 != $count) {
                            echo "\t<div id=\"message2\"><p>Запрошуємо взяти участь у роботі журі конкурсної комісії конференції представників вашого ВНЗ.</p>\n";
                            list_leaders_invite($row['id_u'], false);
                            echo "\t</div>\n";
                        }
                        echo "<div id=\"message2\"><p>Інформація про підсумкову конференцію наведена у Додатку 2.</p>\n</div>";
                        echo "<div id=\"podpis\">Перший проректор ДДТУ,<br>\nГолова галузевої конкурсної комісії<br><br>\n_______________   В.М.Гуляєв</div>\n";
                        echo "<hr>\n";
                    }
                    echo "</div>\n";
                } else {
                    echo "<mark>За данним запитом данних не знайдено! <br> Встановіть відмітку про запрошення хоча б в одній роботі.</mark>";
                }

            }
            break;
        case "invitation_2": //Додаток 1 зі списком студентів ВНЗ, що запрошені до участі у конференції
            {
                $query = "SELECT autors.id as autorNumber, CONCAT(autors.suname,' ',autors.name,' ',autors.lname) as fio_a, univers.univerrod as univer, univers.id as id,autors.curse as curse
                            FROM autors
                              LEFT JOIN univers ON univers.id=autors.id_u
                              LEFT JOIN wa ON autors.id=wa.id_a
                              LEFT JOIN works ON wa.id_w = works.id
                              WHERE works.invitation = 1 AND univers.id <> '1'
                              ORDER BY univer,fio_a";
                $result = mysqli_query($link, $query);
                $row = mysqli_fetch_array($result);
                /*
                $blk_golova = "<div id=\"podpis\">\nГолова галузевої конкурсної комісії,<br>\n"
                    . "професор кафедри електротехніки та електромеханіки ДДТУ<br><br>\n"
                    . "___________ О.В.Садовой</div>";*/
                /*$blk_golova = "<div id=\"podpis\">Перший проректор ДДТУ,<br>\nГолова галузевої конкурсної комісії<br><br>\n_______________   В.М.Гуляєв</div>\n";*/
                $blk_golova = "<div id=\"podpis\">Заступник голови голови галузевої конкурсної комісії,<br>\nзавідувач кафедри електротехніки та електромеханіки ДДТУ<br><br>\n_______________   В.Б.Нізімов</div>\n";
                /*$blk_podpis=""; Раскоментировать для печати отчета*/
                $blk_message = "<div id=\"message\">\n<p>"
                    . "запрошених на підсумкову науково-практичну конференцію Всеукраїнського конкурсу студентських наукових робіт"
                    . " з галузі &quot;Електротехніка та електромеханіка&quot;"
                    . "</p>\n"
                    . "</div>\n";
                $rowStudent ="<li>%s, (№%s)</li>\n";
                $rowStudentArray = array($row['fio_a'],$row['autorNumber']);
                /* начало формирования списка документов */
                echo "<div class=\"v_invitation_2\">\n";
                /*Пишем первый раз*/
                echo "<div id=\"application1\">Додаток 1</div>\n";
                echo "<div id=\"listsudents_title\"><strong>Список студентів</strong></div>\n";
                //Запомним текущий универ чтобы не повторять
                $univer = $row['univer'];
                echo "<div id=\"univer_title\"><em>{$row['univer']}</em></div>\n";
                echo $blk_message;
                echo "<ol>\n";
                vprintf($rowStudent,$rowStudentArray);
                while ($row = mysqli_fetch_array($result)) {
                    $rowStudentArray = array($row['fio_a'],$row['autorNumber']);
                    if ($univer == $row['univer']) {
                        vprintf($rowStudent,$rowStudentArray);
                    } else {
                        echo "</ol>\n";
                        echo $blk_golova;

                        echo "<div id=\"podpis_image\"></div><hr>";
                        echo "<div id=\"application1\">Додаток 1</div>\n";
                        echo "<div id=\"listsudents_title\"><strong>Список студентів</strong></div>\n";
                        $univer = $row['univer'];
                        echo "<div id=\"univer_title\"><em>{$row['univer']}</em></div>\n";
                        echo $blk_message;
                        echo "<ol>\n";
                        vprintf($rowStudent,$rowStudentArray);
                    }

                }
                echo "</ol>\n";
                echo $blk_golova;
                echo "</div>\n";
            }
            break;
        case "ahostel":
            {
                $query = "SELECT autors.id as autorNumber, CONCAT(autors.suname,' ',autors.name,' ',autors.lname) as fio_a, univers.univerrod as univer, univers.id as id,autors.curse as curse
                            FROM autors
                              LEFT JOIN univers ON univers.id=autors.id_u
                              LEFT JOIN wa ON autors.id=wa.id_a
                              LEFT JOIN works ON wa.id_w = works.id
                              WHERE works.invitation = 1 AND univers.id <> '1'
                              ORDER BY univer,fio_a";
                $result = mysqli_query($link, $query);
                //обработаем первый запрос
                $row = mysqli_fetch_array($result);
                echo "<h1>Список студентів на поселеня у гуртожитку</h1>";
                //запомним первый университет из запроса
                $univer = $row['univer'];
                echo "<div id=\"univer_title\"><em>" . $row['univer'] . "</em></div>\n";
                echo "<ol>\n";
                echo "<li>" . $row['fio_a'] . "</li>\n";
                while ($row = mysqli_fetch_array($result)) {
                    if ($univer == $row['univer']) {//Если университет не изменился то напишем автора
                        echo "<li>" . $row['fio_a'] . "</li>\n";
                    } else { //иначе завершим спосок закрыв тег
                        echo "</ol>\n";
                        //запомним новый университет
                        $univer = $row['univer'];
                        //напишем название университета
                        echo "<div id=\"univer_title\"><em>" . $row['univer'] . "</em></div>\n";
                        //начнем список
                        echo "<ol>\n";
                        //запишем первого автора из нового списка
                        echo "<li>" . $row['fio_a'] . "</li>\n";
                    }
                }
                //завершим список
                echo "</ol>\n";
            }
            break;
        case "lhostel":
            {
                $query = "SELECT * FROM v_invitation_3";
                $result = mysqli_query($link, $query);
                //обработаем первый запрос
                $row = mysqli_fetch_array($result);
                echo "<h1>Список керівників на поселеня</h1>";
                //запомним первый университет из запроса
                $univer = $row['univer'];
                echo "<div id=\"univer_title\"><em>" . $row['univer'] . "</em></div>\n";
                echo "<ol>\n";
                echo "<li>" . $row['fio_l'] . "</li>\n";
                while ($row = mysqli_fetch_array($result)) {
                    if ($univer == $row['univer']) {//Если университет не изменился то напишем руководителя
                        echo "<li>" . $row['fio_l'] . "</li>\n";
                    } else { //иначе завершим спосок закрыв тег
                        echo "</ol>\n";
                        //запомним новый университет
                        $univer = $row['univer'];
                        //напишем название университета
                        echo "<div id=\"univer_title\"><em>" . $row['univer'] . "</em></div>\n";
                        //начнем список
                        echo "<ol>\n";
                        //запишем первого руководителя из нового списка
                        echo "<li>" . $row['fio_l'] . "</li>\n";
                    }
                }
                //завершим список
                echo "</ol>\n";
            }
            break;
        case "badge_autors":
            {
                if (filter_input(INPUT_POST, 'works_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)) {
                    $query = "SELECT works.id AS id, works.id_u AS id_u, univers.univerfull AS univerfull, concat(autors.name,'<br>',autors.suname)"
                        . " AS if_a, autors.id AS a_id from (((works join wa on(wa.id_w = works.id))"
                        . " join autors on(wa.id_a = autors.id))"
                        . " join univers on(works.id_u = univers.id)) WHERE ";
                    /* Фильтруем запрос POST и втягиваем от туда массив works_id в котором id авторов
                     * */
                    $arr1 = filter_input(INPUT_POST, 'works_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                    $arr2 = array();
                    $i = 0;
                    //обрамляем каждый id ($s1) в строку и записываем в массив $arr2
                    foreach ($arr1 as $s1) {
                        $arr2[$i] = "autors.id='{$s1}'";
                        $i++;
                    }
                    unset($arr1); // освобождаем память массива
                    /* склеиваем строку из элементов массва $arr2 с помощью строки " OR "*/
                    $query .= implode(" OR ", $arr2) . " GROUP BY autors.id";
                    //var_dump($query);
                } else {
                    //Формируем запрос в БД если пользователь не сделал ни какой отметки
                    $query = "select works.id AS id,works.id_u AS id_u,univers.univerfull AS univerfull,concat(autors.name,'<br>',autors.suname)"
                        . " AS if_a, autors.id AS a_id from (((works join wa on(wa.id_w = works.id))"
                        . " join autors on(wa.id_a = autors.id))"
                        . " join univers on(works.id_u = univers.id)) ";
                    $query .= (isset($_GET['badge'])) ? "WHERE autors.id = '{$_GET['badge']}' GROUP BY a_id" : " WHERE (works.invitation = '1' AND autors.bprint<>'1') GROUP BY a_id  order by works.id,if_a";
                    //echo $query;
                }
                $result = mysqli_query($link, $query);
                echo "<div id=\"badges\"\n>";
                while ($row = mysqli_fetch_array($result)) {
                    $badge = "<div id=\"badge\">\n";
                    $badge .= "<div>Всеукраїнський конкурс СНР з галузі знань</div>\n";
                    $badge .= "<div>&quot;Електротехніка та електромеханіка&quot;</div>\n";
                    //$badge .= "<div id=\"buniverfull\">" . $row['univerfull'] . "</div>\n";
                    $badge .= "<div id=\"idnumber\">" . $row['a_id'] . "</div>\n";
                    $badge .= "<div id=\"bif\">" . $row['if_a'] . "</div>\n";
                    $badge .= "</div>\n";
                    echo $badge;
                }
                echo "</div>\n";
            }
            break;
        case "badge_leaders":
            { //бейджики руководителей
                //Формируем запрос в БД
                if (filter_input(INPUT_POST, 'works_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)) //if(isset($_POST['works_id']))
                {
                    $query = "SELECT CONCAT(leaders.suname,' ',leaders.name,' ',leaders.lname) AS fio,univerfull,position,status,degree"
                        . "FROM leaders\n"
                        . "JOIN univers ON leaders.id_u = univers.id\n"
                        . "LEFT JOIN  positions ON leaders.id_pos = positions.id\n"
                        . "LEFT outer join statuses ON leaders.id_sat = statuses.id\n"
                        . "LEFT outer join degrees ON leaders.id_deg = degrees.id\n"
                        . "WHERE ";
                    $arr1 = filter_input(INPUT_POST, 'works_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                    $arr2 = array();
                    $i = 0;
                    foreach ($arr1 as $s1) {
                        $arr2[$i] = "leaders.id='{$s1}'";
                        $i++;
                    }
                    unset($arr1);
                    $query .= implode(" OR ", $arr2) . " GROUP BY leaders.id";
                    // var_dump($query);
                } else {
                    //var_dump($_GET);
                    $query = "SELECT CONCAT(leaders.suname,' ',leaders.name,' ',leaders.lname) AS fio,univerfull,position,status,degree\n"
                        . "FROM leaders\n"
                        . "JOIN univers ON leaders.id_u = univers.id\n"
                        . "LEFT JOIN  positions ON leaders.id_pos = positions.id\n"
                        . "LEFT outer join statuses ON leaders.id_sat = statuses.id\n"
                        . "LEFT outer join degrees ON leaders.id_deg = degrees.id\n"
                        . "WHERE \n";
                    $query .= (isset($_GET['badge'])) ? "leaders.id = '{$_GET['badge']}' GROUP BY leaders.id" : " leaders.arrival='1' GROUP BY leaders.id";
                    //echo $query;
                }
                echo "<div id=\"badges\">";
                $result = mysqli_query($link, $query);
                while ($row = mysqli_fetch_array($result)) {
                    $badge = "<div id=\"badge\">";
                    $badge .= "<div>Всеукраїнський конкурс студентських наукових робіт з галузі знань</div>";
                    $badge .= "<div>&quot;Електротехніка та електромеханіка&quot;</div>";
                    $badge .= "<div id=\"buniverfull\">" . $row['univerfull'] . "</div>";
                    $str = "";
                    if ($row['degree'] != "-немає-") {
                        $str .= $row['degree'];
                        if ($row['status'] != "-немає-") {
                            $str .= ", " . $row['status'];
                        }
                    } else {
                        $str .= $row['position'];
                    }
                    $badge .= "<div id=\"bfio\">" . $str . "<br>" . $row['fio'] . "</div>";
                    $badge .= "</div>";
                    echo $badge;
                }
                echo "</div>";
            }
            break;
        case "diploms":
            { //Дипломы
                $query = "SELECT
                          autors.place,
                          univers.univerrod,
                          sections.section,
                          works.title,
                          autors.suname AS F,
                          autors.name AS I,
                          autors.lname AS O
                        FROM autors
                          JOIN univers ON univers.id = autors.id_u
                          JOIN wa ON wa.id_a = autors.id
                          JOIN works ON wa.id_w = works.id
                          JOIN sections ON works.id_sec = sections.id
                         WHERE autors.place <> 'D' AND autors.arrival = '1'
                        ORDER BY univers.univerrod";

                $result = mysqli_query($link, $query)
                or die("Полка запиту на друк дипломів: " . mysqli_error($link));
                $total = mysqli_num_rows($result);
                if ($total <> 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<div class=\"diplom\">\n";
                        echo "<div class=\"line1\">" . diplom_place($row['place']) . "</div>\n";
                        echo "<div class=\"line2\">НАГОРОДЖУЄТЬСЯ:</div>\n";
                        echo "<div class=\"line3\">" . student_ka($row['O']) . " " . $row['univerrod'] . "</div>\n";
                        echo "<div class=\"line4\">{$row['F']} {$row['I']} {$row['O']}</div>\n";
                        echo "<div class=\"line5\">за наукову роботу:<br>&quot;{$row['title']}&quot;</div>\n";
                        echo "<div class=\"line6\"> у Всеукраїнському конкурсі студентських наукових <br> робіт 2018/2019 навчального року з галузі знань<br> &quot;Електротехніка та електромеханіка&quot;</div>\n";
                        echo "<div class=\"line7\">Секція &quot;{$row['section']}&quot;</div>\n";
                        echo "<div class=\"line8\">Перший проректор ДДТУ,<br>Голова галузевої конкурсної комісії<br> д.т.н., професор<br></div>\n";
                        echo "<div class=\"line9\"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>В.М.Гуляєв</div>\n";
                        echo "<div class=\"line10\">м. Кам’янське 2019</div>\n";
                        echo "</div>\n";
                    }
                } else {
                    echo "<mark>За данним запитом данних не знайдено!</mark>";
                }
            }
            break;
        case "charters":
            { //Грамоты
                $query = "SELECT
                          autors.place,
                          univers.univerrod,
                          sections.section,
                          works.title,
                          autors.suname AS F,
                          autors.name AS I,
                          autors.lname AS O
                        FROM autors
                          JOIN univers ON univers.id = autors.id_u
                          JOIN wa ON wa.id_a = autors.id
                          JOIN works ON wa.id_w = works.id
                          JOIN sections ON works.id_sec = sections.id
                         WHERE autors.place ='D' AND autors.arrival = '1'
                        ORDER BY univers.univerrod";
                $result = mysqli_query($link, $query)
                or die("Полка запиту : " . mysqli_error($link));
                $total = mysqli_num_rows($result);
                //echo $total;
                if ($total <> 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<div class=\"charters\">\n";
                        echo "<div class=\"line1\">ЗА АКТИВНУ УЧАСТЬ</div>\n";
                        echo "<div class=\"line2\">НАГОРОДЖУЄТЬСЯ:</div>\n";
                        echo "<div class=\"line3\">" . student_ka($row['O']) . " " . $row['univerrod'] . "</div>\n";
                        echo "<div class=\"line4\">{$row['F']} {$row['I']} {$row['O']}</div>\n";
                        echo "<div class=\"line5\">за наукову роботу:<br>&quot;{$row['title']}&quot;</div>\n";
                        echo "<div class=\"line6\"> у Всеукраїнському конкурсі студентських наукових <br> робіт 2018/2019 навчального року з галузі знань<br> &quot;Електротехніка та електромеханіка&quot; </div>\n";
                        echo "<div class=\"line7\">Секція &quot;{$row['section']}&quot;</div>\n";
                        echo "<div class=\"line8\">Перший проректор ДДТУ,<br>Голова галузевої конкурсної комісії,<br> д.т.н., професор</div>\n";
                        echo "<div class=\"line9\"><br><br>В.М.Гуляєв</div>\n";
                        echo "<div class=\"line10\">м. Кам’янське 2019</div>\n";
                        echo "</div><hr>\n";
                    }
                } else {
                    echo "<mark>За данним запитом данних не знайдено!</mark>";
                }

            }
            break;
        case "gratitudes":
            {//Подяки
                $query = "select leaders.id,univers.univerrod AS univer,\n"
                    . "concat(leaders.suname,' ',leaders.name,' ',leaders.lname) AS fio_l,position,status,degree\n"
                    . "from leaders \n"
                    . "join univers on leaders.id_u = univers.id \n"
                    . "LEFT outer JOIN  positions ON leaders.id_pos = positions.id\n"
                    . "LEFT outer join statuses ON leaders.id_sat = statuses.id\n"
                    . "LEFT outer join degrees ON leaders.id_deg = degrees.id\n"
                    . "where ((leaders.arrival = '1') and (univers.id <> 1))\n"
                    . "group by leaders.id_u,fio_l\n"
                    . "order by univer,fio_l";
                $result = mysqli_query($link, $query)
                or die("Полка запиту : " . mysqli_error($link));
                $total = mysqli_num_rows($result);
                //echo $total;
                if ($total <> 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<div class=\"gratitudes\">\n";
                        $str = "";
                        if ($row['degree'] != "-немає-") {
                            $str .= $row['degree'];
                            if ($row['status'] != "-немає-") {
                                $str .= ", " . $row['status'];
                            }
                        } else {
                            $str .= $row['position'];
                        }
                        echo "<div class=\"line1\">нагороджується {$str}</div>\n";
                        echo "<div class=\"line2\">{$row['univer']}</div>\n";
                        echo "<div class=\"line3\">" . strtoupper($row['fio_l']) . "</div>\n";
                        echo "<div class=\"line4\">за активну участь в підготовці та проведенні підсумкової конференції</div>\n";
                        echo "<div class=\"line5\">&quot;Електротехніка та електромеханіка - 2019&quot;</div>\n";
                        echo "<div class=\"line6\">Всеукраїнському конкурсі студентських наукових <br> робіт 2018/2019 навчального року з галузі<br> &quot;Електротехніка та електромеханіка&quot;</div>\n";
                        echo "<div class=\"line8\">Перший проректор ДДТУ,<br>Голова галузевої конкурсної комісії,<br> д.т.н., професор</div>\n";
                        echo "<div class=\"line9\"><br><br>В.М.Гуляєв</div>\n";
                        echo "<div class=\"line10\">м. Кам’янське 2019</div>\n";


                        echo "</div><hr>\n";
                    }
                } else {
                    echo "<mark>За данним запитом данних не знайдено!</mark>";
                }
            }
            break;

    }//switch
}
?>
</body>
</html>