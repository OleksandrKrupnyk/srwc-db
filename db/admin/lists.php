<?php
/**
 * @author    studyjquery
 * @copyright 2015
 */

use zukr\base\Base;
use zukr\base\helpers\PersonHelper;
use zukr\place\PlaceHelper;

require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';
header('Content-Type: text/html; charset=utf-8');
session_name('tzLogin');
session_start();
global $link;
global $FROM;
//переменная для определения предка вызова сценария
$FROM = trim(urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
// Прочитать насторйки программы из БД
Base::init();
$settings = Base::$param;
if (Base::$user->getUser()->isGuest()) {
    Go_page('index.php');
}
$ph = PlaceHelper::getInstance();
?>
<!DOCTYPE html>
<html lang="ua">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="icon" type="image/png" href="../images/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="../images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../images/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="../images/favicon-192x192.png" sizes="192x192">
    <link rel="manifest" href="manifest.json">
    <link href="../css/print.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/admin.js" async></script>
    <title>ДРУКУВАТИ <?= Base::$app->app_name ?></title>
</head>
<body>
<?php
try {
    switch (filter_input(INPUT_GET, 'list')) {
        case 'adress':
            {
                $query = "SELECT univers.univerfull, univers.adress, univers.zipcode 
                      FROM univers WHERE univers.invite = '1' GROUP BY univerfull ASC";
                $result = mysqli_query($link, $query);
                echo '<div class="adress2">
                            <header>Список розсилки 1-го інформаційного повідомлення <br>
                            Всеукраїнського конкурсу студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</header>
                            <div id="table">
                            <table><tr><th>№</th><th>Кому</th><th>Адреса</th></tr>';
                $i = 1;
                while ($row = mysqli_fetch_array($result)) {
                    echo table_row_list_address2($i, $row);
                    $i++;
                    if (($i === 23) || ($i === 48)) {
                        echo '</table></div><div class="tableprotocol"></div>
<div class="adress2"><div id="table"><table><tr><th>№</th><th>Кому</th><th>Адреса</th></tr>';
                    }
                }
                echo '</table>
</div><div id="prorector">Перший проректор ДДТУ<br></div><div id="prorectorNAME">В.М. Гуляєв</div></div>';
            }
            break;
        case 'adress2':
            {//ТОлько те работы у коротых есть приглашенные работы минус ДДТУ
                $query = "SELECT univers.*
                      FROM univers
                      LEFT JOIN works ON univers.id = works.id_u
                      WHERE works.invitation = '1' AND univers.id != '1'
                      GROUP BY univers.univer
                      ORDER BY univers.univer ";
                $result = mysqli_query($link, $query);
                echo '<div class="adress2">'
                    . '<header>Список розсилки 2-го інформаційного повідомлення <br>Всеукраїнського конкурсу студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</header>'
                    . '<div id="table"><table>'
                    . '<tr><th>№</th><th>Кому</th><th>Адреса</th></tr>';
                $i = 1;
                while ($row = mysqli_fetch_array($result)) {

                    echo table_row_list_address2($i, $row);
                    $i++;
                    if (($i === 21) || ($i === 47)) {
                        echo '</table></div>'
                            . '<div class = "tableprotocol"></div>'
                            . '<div class="adress2">'
                            . '<div id="table"><table>'
                            . '<tr><th>№</th><th>Кому</th><th>Адреса</th></tr>';
                    }
                }
                echo '</table></div>'
                    . '<div id="prorector">Перший проректор ДДТУ<br></div>'
                    . '<div id="prorectorNAME">В.М. Гуляєв</div>'
                    . '</div>';
            }
            break;
        case 'envelope'://Конерти 1-го інфомаційного повідомлення
            {
                $query = "SELECT univers.univerfull,univers.adress,univers.zipcode FROM univers WHERE univers.invite = '1' GROUP BY univerfull ASC";
                $result = mysqli_query($link, $query);
                echo '<div class="envelope">';
                while ($row = mysqli_fetch_array($result)) {
                    $envelop = <<<__ENVELOP__
    <div id="fromAdress">
        <strong><ins>Всеукраїнський конкурс студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</ins></strong><br>
        <em>вул.&nbsp;Дніпробудівська,2 м.&nbsp;Кам’янське,
        <br>Дніпропетровська обл.</em><br><strong>51918</strong>
    </div>
    <div id="whomAdress">
        <strong><ins>{$row['univerfull']}</ins></strong><br>
        <em>{$row['adress']}</em><br>
        <strong>{$row['zipcode']}</strong>
    </div>
    <hr>
__ENVELOP__;
                    echo $envelop;
                }
                echo '</div>';
            }
            break;
        case 'envelope2'://Конерти 2-го інфомаційного повідомлення
            {
                $query = "SELECT univers.* 
                          FROM univers 
                              LEFT JOIN works ON univers.id = works.id_u 
                          WHERE works.invitation = '1' AND univers.id != '1' 
                          GROUP BY univers.univer 
                          ORDER BY univers.univer;";
                $result = mysqli_query($link, $query);
                echo '<div class="envelope">';
                while ($row = mysqli_fetch_array($result)) {
                    $envelop = <<<__ENVELOP__
    <div id="fromAdress">
        <strong><ins>Всеукраїнський конкурс студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</ins></strong><br>
        <em>вул.&nbsp;Дніпробудівська,2 м.&nbsp;Кам’янське,
        <br>Дніпропетровська обл.</em><br><strong>51918</strong>
    </div>
    <div id="whomAdress">
        <strong><ins>{$row['univerfull']}</ins></strong><br>
        <em>{$row['adress']}</em><br>
        <strong>{$row['zipcode']}</strong>
    </div>
    <hr>
__ENVELOP__;
                    echo $envelop;
                }
                echo '</div>';
            }
            break;
        case 'invitation_1'://Запрошення
            {
                $query = "SELECT 
       v_take_part.id_u AS id_u, 
       v_take_part.count_take_part AS count_take_part,
        v_invitation.count_invitation AS count_invitation, 
       univers.univerrod AS univerrod, 
       univers.rector_r AS rector_r, 
       univers.posada AS posada 
                FROM `v_take_part` 
                    LEFT JOIN v_invitation on v_take_part.id_u = v_invitation.id_u 
                    LEFT JOIN univers on v_take_part.id_u = univers.id
                    WHERE (univers.id <> '1') AND (count_invitation > 0) 
                    ORDER BY univerfull ";
                //echo $query;
                $result = mysqli_query($link, $query);
                if (!empty($result)) {
                    $total = mysqli_num_rows($result);
                    if ($total !== 0) {
                        echo "<div class=\"v_invitation_1\">\n";
                        while ($row = mysqli_fetch_array($result)) {
                            $rector = (!empty($row['rector_r']))
                                ? $row['rector_r']
                                : "<mark><a href=\"action.php?action=univer_edit&id_u={$row['id_u']}&FROM={$FROM}\">ЗАПОВНІТЬ ДАНІ ПРО ВНЗ</a></mark>";
                            //$invitation = ($row['count_invitation'] != '') ? $row['count_invitation'] : "<mark><a href=\"action.php?action=all_view#id_u{$row['id_u']}\">ЗАПРОСИТИ?</a></mark>";
                            $invitation = '';
                            // Печатать Шапку университета
                            PrintGerb($empty = true);//Печатет данные бланка Герб и т.д.
                            $blk_rectory = "<div id='rectory'>{$row['posada']} {$row['univerrod']}<br>{$rector}</div>";
                            echo $blk_rectory;
                            $blk_message = '<div id="message"><p>Галузева конкурсна комісія Всеукраїнського конкурсу студентських наукових робіт'
                                . ' з галузі &quot;Електротехніка та електромеханіка&quot; запрошує до участі у підсумковій науково-практичній конференції авторів кращих робіт. </p>'
                                . '<p>Список запрошених авторів наукових робіт наведено у Додатку 1.</p>'
                                . '<p>Відповідно до &quot;Положення про  проведення Всеукраїнського конкурсу студентських наукових робіт  з природничих,'
                                . " технічних та гуманітарних наук&quot; від {$settings->DATEPO} №{$settings->ORDERPO} автор наукової роботи, який  не  брав  участі  у  підсумковій"
                                . ' науково-практичній конференції, не може бути претендентом на нагородження.</p>'
                                . '</div>';
                            echo $blk_message;
                            $query2 = "SELECT leaders.invitation  FROM leaders WHERE id_u = '{$row['id_u']}' AND invitation = TRUE";
                            $result2 = mysqli_query($link, $query2)
                            or die('Invalid query: ' . mysqli_error($link));
                            $count = mysqli_num_rows($result2);
                            // Список журі від ВНЗ
                            if (0 !== $count) {
                                echo '<div id="message2"><p>Запрошуємо взяти участь у роботі журі конкурсної комісії конференції представників вашого ВНЗ.</p>';
                                list_leaders_invite($row['id_u'], false);
                                echo '</div>';
                            }
                            echo '<div id="message2"><p>Інформація про підсумкову конференцію наведена у Додатку 2.</p></div>'
                                . '<div id="podpis">Перший проректор ДДТУ,<br>Голова галузевої конкурсної комісії<br><br>_______________   В.М.Гуляєв</div><hr>';
                        }
                        echo '</div>';
                    } else {
                        echo '<mark>За данним запитом данних не знайдено! <br> Встановіть відмітку про запрошення хоча б в одній роботі.</mark>';
                    }
                }

            }
            break;
        case 'invitation_2': //Додаток 1 зі списком студентів ВНЗ, що запрошені до участі у конференції
            {
                $query = "SELECT autors.id as autorNumber, 
                                 CONCAT(autors.suname,' ',autors.name,' ',autors.lname) as fio_a, 
                                 univers.univerrod as univer, 
                                 univers.id as id,
                                 autors.curse as curse
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
                $blk_golova = '<div id="podpis">Заступник голови голови галузевої конкурсної комісії,<br>завідувач кафедри електротехніки та електромеханіки ДДТУ<br><br>_______________   В.Б.Нізімов</div>';
                /*$blk_podpis=""; Раскоментировать для печати отчета*/
                $blk_message = '<div id="message"><p>'
                    . 'запрошених на підсумкову науково-практичну конференцію Всеукраїнського конкурсу студентських наукових робіт'
                    . ' з галузі &quot;Електротехніка та електромеханіка&quot;'
                    . "</p>"
                    . "</div>";
                $rowStudent = "<li>%s, (№%s)</li>";
                $rowStudentArray = [$row['fio_a'], $row['autorNumber']];
                /* начало формирования списка документов */
                echo '<div class="v_invitation_2">';
                /*Пишем первый раз*/
                echo '<div id="application1">Додаток 1</div><div id="listsudents_title"><strong>Список студентів</strong></div>';
                //Запомним текущий универ чтобы не повторять
                $univer = $row['univer'];
                echo "<div id=\"univer_title\"><em>{$row['univer']}</em></div>"
                    . $blk_message
                    . '<ol>';
                vprintf($rowStudent, $rowStudentArray);
                while ($row = mysqli_fetch_array($result)) {
                    $rowStudentArray = [$row['fio_a'], $row['autorNumber']];
                    if ($univer === $row['univer']) {
                        vprintf($rowStudent, $rowStudentArray);
                    } else {
                        echo '</ol>'
                            . $blk_golova
                            . '<div id="podpis_image"></div>' .
                            '<hr>'
                            . '<div id="application1">Додаток 1</div>'
                            . '<div id="listsudents_title"><strong>Список студентів</strong></div>';
                        $univer = $row['univer'];
                        echo '<div id="univer_title"><em>' . $row['univer'] . '</em></div>' . $blk_message . '<ol>';
                        vprintf($rowStudent, $rowStudentArray);
                    }

                }
                echo '</ol>' . $blk_golova . '</div>';
            }
            break;
        case 'ahostel':
            {
                $query = "SELECT autors.id AS autorNumber, 
                            CONCAT(autors.suname,' ',autors.name,' ',autors.lname) AS fio_a, 
                            univers.univerrod AS univer, 
                            univers.id AS id,
                            autors.curse AS curse
                            FROM autors
                              LEFT JOIN univers ON univers.id=autors.id_u
                              LEFT JOIN wa ON autors.id=wa.id_a
                              LEFT JOIN works ON wa.id_w = works.id
                              WHERE works.invitation = 1 AND univers.id <> '1'
                              ORDER BY univer,fio_a";
                $result = mysqli_query($link, $query);
                $row = mysqli_fetch_array($result);
                echo '<h1>Список студентів на поселеня у гуртожитку</h1>';
                $univer = $row['univer'];
                echo '<div id="univer_title"><em>' . $row['univer'] . "</em></div>";
                echo "<ol>";
                echo '<li>' . $row['fio_a'] . "</li>";
                while ($row = mysqli_fetch_array($result)) {
                    if ($univer === $row['univer']) {
                        echo '<li>' . $row['fio_a'] . '</li>';
                    } else {
                        $univer = $row['univer'];
                        echo '</ol>';
                        echo '<div id="univer_title"><em>' . $row['univer'] . '</em></div>' .
                            '<ol>' .
                            '<li>' . $row['fio_a'] . '</li>';
                    }
                }
                echo '</ol>';
            }
            break;
        case 'lhostel':
            {
                $listLeaders = (new \zukr\leader\LeaderRepository())->getListLeadersForHostel();
                $listUniver = \zukr\base\helpers\ArrayHelper::group($listLeaders, 'univerrod');
                if (!empty($listUniver)) {
                    $txt = [];
                    $txt[] = '<h1>Список керівників на поселеня</h1>';
                    foreach ($listUniver as $univer => $listLeaders) {
                        $txt[] = '<div id="univer_title"><em>' . $univer . '</em></div>';
                        $list = [];
                        foreach ($listLeaders as $leader) {
                            $list [] = '<li>' . PersonHelper::getFullName($leader) . '</li>';
                        }
                        $txt[] = '<ol>' . implode('', $list) . '</ol>';
                    }
                    echo implode('', $txt);
                } else {
                    echo '<mark>За данним запитом данних не знайдено!</mark>';
                }
            }
            break;
        case 'badge_author':
            {
                if (
                    !empty(($badge = filter_input(INPUT_GET, 'badge', FILTER_VALIDATE_INT)))
                ) {
                    $query = "SELECT univers.univerfull AS univerfull, concat(autors.name, '<br>', autors.suname) AS if_a, autors.id AS a_id FROM autors JOIN univers ON (autors.id_u = univers.id)
                        WHERE autors.id = {$badge}";
                } elseif (
                    !empty($listAutorsId = filter_input(INPUT_POST, 'works_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY))
                ) {
                    sort($listAutorsId);
                    $query = "SELECT univers.univerfull AS univerfull, concat(autors.name, '<br>', autors.suname) AS if_a, autors.id AS a_id FROM autors JOIN univers ON (autors.id_u = univers.id) ";
                    $query .= ' WHERE autors.id IN (' . implode(',', $listAutorsId) . ') GROUP BY a_id';
                } elseif (!isset($_GET['badge'])) {
                    $query = "
                            SELECT works.id AS id, 
                            works.id_u AS id_u, univers.univerfull AS univerfull, 
                                concat(autors.name,'<br>',autors.suname) 
                              AS if_a, autors.id AS a_id 
                            FROM (((works join wa on(wa.id_w = works.id)) 
                                join autors on(wa.id_a = autors.id))  
                                join univers on(works.id_u = univers.id)) 
                            WHERE (works.invitation = '1' AND autors.bprint<>'1') 
                            GROUP BY works.id,a_id  
                            ORDER BY works.id,if_a;";
                }
                echo '<div class="badges">';
                if (!empty($query)) {
                    $result = mysqli_query($link, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        $badge = '<div class="badge"><div>Всеукраїнський конкурс СНР з галузі знань</div><div>&quot;Електротехніка та електромеханіка&quot;</div>';
                        $badge .= '<div class="id-number">' . $row['a_id'] . '</div><div class="bif">' . $row['if_a'] . '</div></div>';
                        echo $badge;
                    }
                }
                echo '</div>';
            }
            break;
        case 'badge_leader':
            { //бейджики руководителей
                //Формируем запрос в БД
                $query = "SELECT CONCAT(leaders.suname,' ',leaders.name,' ',leaders.lname) AS fio,univerfull,position,status,degree 
                            FROM leaders 
                                JOIN univers ON leaders.id_u = univers.id 
                                LEFT JOIN  positions ON leaders.id_pos = positions.id 
                                LEFT outer join statuses ON leaders.id_sat = statuses.id 
                                LEFT outer join degrees ON leaders.id_deg = degrees.id";
                if (
                !empty($listLeadersId = filter_input(INPUT_POST, 'works_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY))
                ) {
                    sort($listLeadersId);
                    $queryWhere = ' WHERE leaders.id IN (' . implode(',', $listLeadersId) . ') GROUP BY leaders.id';
                    $query .= $queryWhere;
                } else {
                    $query .= (isset($_GET['badge']))
                        ? " WHERE leaders.id = '{$_GET['badge']}' GROUP BY leaders.id"
                        : " WHERE leaders.arrival='1' GROUP BY leaders.id";
                    //echo $query;
                }
                echo '<div class="badges">';
                 if (!empty($query)) {
                $result = mysqli_query($link, $query);
                     while ($row = mysqli_fetch_array($result)) {
                         $badge = '<div class="badge">'
                             . '<div>Всеукраїнський конкурс студентських наукових робіт з галузі знань</div>'
                             . '<div>&quot;Електротехніка та електромеханіка&quot;</div>'
                             . '<div class="buniverfull">' . $row['univerfull'] . '</div>';
                         $str = '';
                         if ($row['degree'] !== '-немає-') {
                             $str .= $row['degree'];
                             if ($row['status'] !== '-немає-') {
                                 $str .= ', ' . $row['status'];
                             }
                         } else {
                             $str .= $row['position'];
                         }
                         $badge .= '<div class="bfio">' . $str . '<br>' . $row['fio'] . '</div>';
                         $badge .= '</div>';
                         echo $badge;
                     }
                 }
                echo '</div>';
            }
            break;
        case 'diploms':
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
                or die('Полка запиту на друк дипломів: ' . mysqli_error($link));
                $total = mysqli_num_rows($result);
                if ($total !== 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<div class="diplom">'
                            . '<div class="line1">' . $ph->diplomString($row['place']) . '</div>'
                            . '<div class="line2">НАГОРОДЖУЄТЬСЯ:</div>'
                            . '<div class="line3">' . PersonHelper::student_ka($row['O']) . ' ' . $row['univerrod'] . '</div>'
                            . '<div class="line4">' . $row['F'] . ' ' . $row['I'] . ' ' . $row['O'] . '</div>'
                            . '<div class="line5">за наукову роботу:<br>&quot;' . $row['title'] . '&quot;</div>'
                            . '<div class="line6"> у Всеукраїнському конкурсі студентських наукових <br> робіт ' . $settings->NYEARS . ' навчального року з галузі знань<br> &quot;Електротехніка та електромеханіка&quot;</div>'
                            . '<div class="line7">Секція &quot;' . $row['section'] . '&quot;</div>'
                            . '<div class="line8">Перший проректор ДДТУ,<br>Голова галузевої конкурсної комісії<br> д.т.н., професор<br></div>'
                            . '<div class="line9"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>В.М.Гуляєв</div>'
                            . '<div class="line10">м. Кам’янське ' . $settings->YEAR . '</div>' .
                            '</div>';
                    }
                } else {
                    echo '<mark>За данним запитом данних не знайдено!</mark>';
                }
            }
            break;
        case 'charters':
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
                or die('Полка запиту : ' . mysqli_error($link));
                $total = mysqli_num_rows($result);
                //echo $total;
                if ($total !== 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<div class="charters">'
                            . '<div class="line1">ЗА АКТИВНУ УЧАСТЬ</div>'
                            . '<div class="line2">НАГОРОДЖУЄТЬСЯ:</div>'
                            . '<div class="line3">' . PersonHelper::student_ka($row['O']) . ' ' . $row['univerrod'] . '</div>'
                            . '<div class="line4">' . $row['F'] . ' ' . $row['I'] . ' ' . $row['O'] . '</div>'
                            . '<div class="line5">за наукову роботу:<br>&quot;' . $row['title'] . '&quot;</div>'
                            . '<div class="line6"> у Всеукраїнському конкурсі студентських наукових <br> робіт ' . $settings->NYEARS . ' навчального року з галузі знань<br> &quot;Електротехніка та електромеханіка&quot; </div>'
                            . '<div class="line7">Секція &quot;' . $row['section'] . '&quot;</div>'
                            . '<div class="line8">Перший проректор ДДТУ,<br>Голова галузевої конкурсної комісії,<br> д.т.н., професор</div>'
                            . '<div class="line9"><br><br>В.М.Гуляєв</div>'
                            . '<div class="line10">м. Кам’янське ' . $settings->YEAR . '</div>'
                            . '</div><hr>';
                    }
                } else {
                    echo '<mark>За данним запитом данних не знайдено!</mark>';
                }

            }
            break;
        case 'gratitudes':
            {//Подяки
                $query = "SELECT leaders.id,univers.univerrod AS univer, CONCAT(leaders.suname,' ',leaders.name,' ',leaders.lname) AS fio_l,position,status,degree 
                          FROM leaders  
                              JOIN univers on leaders.id_u = univers.id  
                              LEFT outer JOIN  positions ON leaders.id_pos = positions.id
                              LEFT outer join statuses ON leaders.id_sat = statuses.id 
                              LEFT outer join degrees ON leaders.id_deg = degrees.id 
                          WHERE ((leaders.arrival = '1') and (univers.id <> 1)) 
                          GROUP BY leaders.id_u,fio_l 
                          ORDER BY univer, fio_l";
                $result = mysqli_query($link, $query)
                or die('Полка запиту : ' . mysqli_error($link));
                $total = mysqli_num_rows($result);
                //echo $total;
                if ($total !== 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<div class="gratitudes">';
                        $str = '';
                        if ($row['degree'] !== '-немає-') {
                            $str .= $row['degree'];
                            if ($row['status'] !== '-немає-') {
                                $str .= ', ' . $row['status'];
                            }
                        } else {
                            $str .= $row['position'];
                        }
                        echo '<div class="line1">нагороджується ' . $str . '</div>'
                            . '<div class="line2">' . $row['univer'] . '</div>'
                            . '<div class="line3">' . strtoupper($row['fio_l']) . '</div>'
                            . '<div class="line4">за активну участь в підготовці та проведенні підсумкової конференції</div>'
                            . '<div class="line5">&quot;Електротехніка та електромеханіка - ' . $settings->YEAR . '&quot;</div>'
                            . '<div class="line6">Всеукраїнському конкурсі студентських наукових <br> робіт ' . $settings->NYEARS . ' навчального року з галузі<br> &quot;Електротехніка та електромеханіка&quot;</div>'
                            . '<div class="line8">Перший проректор ДДТУ,<br>Голова галузевої конкурсної комісії,<br> д.т.н., професор</div>'
                            . '<div class="line9"><br><br>В.М.Гуляєв</div>'
                            . '<div class="line10">м. Кам’янське ' . $settings->YEAR . '</div>'
                            . '</div><hr>';
                    }
                } else {
                    echo '<mark>За данним запитом данних не знайдено!</mark>';
                }
            }
            break;
        default:
            echo '<mark>Невірний запист</mark>';

    }//switch
} catch (Exception $e) {
    Base::$log->critical($e->getMessage());
}
?>
</body>
</html>