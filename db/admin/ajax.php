<?php

use zukr\api\actions\ApiActionsInterface;
use zukr\api\ApiHelper;
use zukr\base\Base;

header("Content-Type: text/html; charset=utf-8");
require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';
global $link;

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRIPPED);
$id_u = filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT);
$id_w = filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT);
// Запрос на измемение данных в работе
if (isset($_POST['id_w']) && $_POST['action'] === 'invitation') {//Запрос на обновление данных по приглашению
    $query = "UPDATE `works` SET \n"
        . "`invitation` ='" . $_POST['invit'] . "'\n"
        . "WHERE `id` = '" . $_POST['id_w'] . "'";
    //echo $query;
    $result = mysqli_query($link, $query)
    or die("Помилка запиту: " . mysqli_error($link));
    log_action($_POST['action'], "works", $_POST['id_w']);
}

if (isset($_POST['id_w']) && $_POST['action'] === "id_sec") {
    $query = "UPDATE `works` SET \n"
        . "`id_sec` ='" . $_POST['id_sec'] . "'\n"
        . "WHERE `id` = '" . $_POST['id_w'] . "'";
    //echo $query;
    $result = mysqli_query($link, $query)
    or die("Помилка запиту: " . mysqli_error($link));
    log_action($_POST['action'], "works", $_POST['id_w']);
}
try {
    $apih = ApiHelper::getInstance();
    /** ApiActionsInterface $classObj */
    $classObj = $apih->getActionByName($action);
    if ($classObj instanceof ApiActionsInterface) {
        $classObj->init();
        echo $classObj->execute();
    }
} catch (\Exception $e) {
    Base::$log->error($e->getMessage());
}


switch ($action) {
    //Обработка Запроса на список работ в вузе
    case 'selunivers':
        {
            if (!empty($id_u) && !isset($_POST['id_w'])) {
                $select_id_w = $_POST['select_id_w'] ?? '';
                echo list_works_of_univer($_POST['id_u'], 'title', $select_id_w, 5);
            }
        }
        break;
    /* Обработка запроса на отметку в графе прибытие */
    case 'add_arrival':
        {
            if (isset($_POST['id_a'])) {
                $query = "UPDATE `autors` SET `arrival` = '1' \n"
                    . "WHERE `autors`.`id` ='" . $_POST['id_a'] . "'";
                log_action($_POST['action'], "autors", $_POST['id_a']);
            } elseif (isset($_POST['id_l'])) {
                $query = "UPDATE `leaders` SET `arrival` = '1' "
                    . "WHERE `leaders`.`id` ='{$_POST['id_l']}'";
                log_action($_POST['action'], "leaders", $_POST['id_l']);
            }
            mysqli_query($link, "SET NAMES 'utf8'");
            mysqli_query($link, "SET CHARACTER SET 'utf8'");
            $result = mysqli_query($link, $query)
            or die("Помилка запиту: " . mysqli_error($link));
        }
        break;
    /* Убирает отметку о прибитии на конференцию автора или руководителя */
    case 'rem_arrival':
        {
            if (isset($_POST['id_a'])) {
                $query = "UPDATE `autors` SET `arrival` = '0' WHERE `autors`.`id` ='{$_POST['id_a']}'";
                log_action($_POST['action'], "autors", $_POST['id_a']);
            } elseif (isset($_POST['id_l'])) {
                $query = "UPDATE `leaders` SET `arrival` = '0' WHERE `leaders`.`id` ='{$_POST['id_l']}'";
                log_action($_POST['action'], "leaders", $_POST['id_l']);
            }
            mysqli_query($link, "SET NAMES 'utf8'");
            mysqli_query($link, "SET CHARACTER SET 'utf8'");
            $result = mysqli_query($link, $query)
            or die("Помилка запиту: " . mysqli_error($link));
        }
        break;
    /*     * ------------------------------------------------------- */
    case 'selwork':
        {
            if (isset($_POST['id_w'])) {
                //Запросить кол. руководителей по работе
                $col_l = count_la("wl", $_POST['id_w']);
                if ($col_l != 0) {
                    echo(list_leader_or_autors_str($_POST['id_w'], "wl", true));
                } elseif ($col_l == 0) {
                    echo '<span class="info">Відсутні</span>';
                }
                echo "!"; //символ разбиения строки не просто так написан
                if ($col_l < N_LEADERS) {
                    $i = 1;
                    while ($col_l < N_LEADERS) {
                        list_fio("leaders", "leader[" . $i . "]", $_POST['id_u'], 1);
                        echo "<br>";
                        $i++;
                        $col_l++;
                    }
                    echo "<a href=\"action.php?action=leader_add&id_u=" . $_POST['id_u'] . "\" title=\"Внесення в базу даних керівника\">Створити</a>";
                } else {
                    echo '<span class="info">Досить</span>';
                }
                echo '!';//символ разбиения строки не просто так написан

                $col_a = count_la("wa", $_POST['id_w']);
                if ($col_a != 0) {
                    echo(list_leader_or_autors_str($_POST['id_w'], "wa", true));
                } elseif ($col_a == 0) {
                    echo "<span class=\"info\">Відсутні</span>";
                }
                echo '!';//символ разбиения строки не просто так написан
                if ($col_a < N_AUTORS) {
                    $i = 1;
                    while ($col_a < N_AUTORS) {
                        list_fio("autors", "autor[" . $i . "]", $_POST['id_u'], 1);
                        echo "<br>";
                        $i++;
                        $col_a++;
                    }

                    echo "<a href=\"action.php?action=author_add&id_u=" . $_POST['id_u'] . "&id_w=" . $_POST['id_w'] . "\" title=\"Внесення в базу даних автора\">Створити</a>";
                } else {
                    echo '<span class="info">Досить</span>';
                }
            }
        }
        break;
    /**
     * Получить список всех руководителей и авторов данного вуза
     */
    case 'getlists':
        {
            list_fio('autors', 'autor', $_POST['id_u'], 1, $selecttag = false);
            echo '!';
            list_fio('leaders', 'leaders', $_POST['id_u'], 1, $selecttag = false);
        }
        break;

    case 'getlistinvitationleaders':
        {
            list_leaders_invite($_POST['id_u']);
        }
        break;
    case 'list_all':
        {
            list_fio('autors', 'autor', $_POST['id_u'], 1, $selecttag = true);
            echo "!";
            list_fio('leaders', 'leaders', $_POST['id_u'], 1, $selecttag = true);
        }
        break;


    /**
     * Вернуть список авторов и руководителей по выбраному ВУЗ и отметить которые прибыли
     */
    case 'list_al':
        {
            if (isset($_POST['id_u'])) {
                //Первый запрос в таблицу авторов
                $query = "SELECT `autors`.`id`,
                      CONCAT(`autors`.`suname`,' ',`autors`.`name`,' ',`autors`.`lname`) AS fio_a, `autors`.`arrival`,`works`.`invitation`  
                      FROM `autors` 
                      RIGHT OUTER JOIN `wa` ON `autors`.`id` =  `wa`.`id_a`
                      JOIN  `works` ON `wa`.`id_w` = `works`.`id` 
                      WHERE `autors`.`id_u` = {$_POST['id_u']} AND `works`.`invitation` = '1' 
                      GROUP BY fio_a 
                      ORDER BY fio_a";
                mysqli_query($link, "SET NAMES 'utf8'");
                mysqli_query($link, "SET CHARACTER SET 'utf8'");
                $result = mysqli_query($link, $query)
                or die("Помилка запиту: " . mysqli_error($link));

                $str = "";
                while ($row = mysqli_fetch_array($result)) {
                    $Class = ($row['arrival'] == 1) ? "class=\"option-arrival\"" : "";
                    $str .= "<li alt=\"" . $row['id'] . "\" " . $Class . ">" . $row['fio_a'] . "</li>\n";
                }
                $str .= "!"; //символ разделитель
                // Второй запрос в таблицу уководителей
                $query = "SELECT `leaders`.`id`, 
                    CONCAT(`leaders`.`suname`,' ',`leaders`.`name`,' ',`leaders`.`lname`) as fio_a, `leaders`.`arrival`  
                    FROM `leaders` 
                    RIGHT OUTER JOIN `wl` ON `leaders`.`id` =  `wl`.`id_l` 
                    WHERE `leaders`.`id_u` = {$_POST['id_u']} 
                    GROUP BY `fio_a` 
                    ORDER BY `fio_a`";
                mysqli_query($link, "SET NAMES 'utf8'");
                mysqli_query($link, "SET CHARACTER SET 'utf8'");
                $result = mysqli_query($link, $query)
                or die('Помилка запиту: ' . mysqli_error($link));
                $str .= "";
                while ($row = mysqli_fetch_array($result)) {
                    $Class = ($row['arrival'] == 1) ? "class=\"option-arrival\"" : "";
                    $str .= "<li alt=\"" . $row['id'] . "\" " . $Class . ">" . $row['fio_a'] . "</li>\n";
                }

                echo $str;
            }
        }
        break;

    /** Запрос на изменение секции куда прислана работа */
    case 'id_sec':
        {
            if (isset($_POST['id_w'])) {
                $query = "UPDATE `works` SET `id_sec` ='{$_POST['id_sec']}' WHERE `id` = '{$_POST['id_w']}'";
                //echo $query;
                $result = mysqli_query($link, $query)
                or die('Помилка запиту: ' . mysqli_error($link));
                log_action($_POST['action'], "works", $_POST['id_w']);
            }
        }
        break;
    /** Запрос на изменение аудитории заседания секции */
    case 'change_room':
        {
            if (isset($_POST['id_sec'])) {
                $query = "UPDATE `sections` SET `room` = '{$_POST['room'] }' WHERE `id` = '{$_POST['id_sec']}'";
                //echo $query;
                $result = mysqli_query($link, $query)
                or die("Помилка запиту: " . mysqli_error($link));
                log_action($_POST['action'], "sections", $_POST['id_sec']);
            }
        }
        break;
    /** Установление отметки об участии в конференции в записи о работе на основе данных прибывших*/
    case 'update_arrival_works':
        {
            $query = "UPDATE works dest , 
                 (SELECT `works`.`id`,`autors`.`arrival` as arr 
                  FROM works 
                  JOIN wa on works.id=wa.id_w 
                  JOIN autors on wa.id_a=autors.id 
                  WHERE autors.arrival ='1' AND works.invitation='1') src 
                  SET dest.arrival=src.arr 
                  WHERE dest.id=src.id";
            //echo $query;
            $result = mysqli_query($link, $query)
            or die('Помилка запиту на оновлення відміток про прибуття роботи: ' . mysqli_error($link));
            $query = "SELECT ROW_COUNT()";
            $result = mysqli_query($link, $query);
            $row = mysqli_fetch_array($result);
            echo $row[0];
        }
        break;
    /** */
    case 'setplace':
        {
            $query = "UPDATE autors  SET place = '{$_POST['place']}' WHERE `id`='{$_POST['id_a']}'";
            $result = mysqli_query($link, $query)
            or die('Помилка запиту на оновлення місця автора у конкурсі: ' . mysqli_error($link));
            echo $query;
        }
        break;
    case 'invitationUniver':
        {
            $query = "UPDATE `univers` SET `invite` ='{$_POST['invite']}' WHERE `id` = '{$_POST['id_u']}'";
            //echo $query;
            $result = mysqli_query($link, $query)
            or die('Помилка запиту на оновлення відмітки про надсилання 1-го ІП: ' . mysqli_error($link));
        }
        break;

    case 'invitationLeader':
        {
            $query = "UPDATE `leaders` SET `invitation` ='{$_POST['invitation']}' WHERE `id` = '{$_POST['id_l']}'";
            $result = mysqli_query($link, $query)
            or die('Помилка запиту на оновлення відмітки про надсилання 1-го ІП: ' . mysqli_error($link));
        }
        break;
}//end switch


/**
 * Возвращает строку htm список руководителей или авторов разделенных знаком переноса
 *
 * $href == true выделяет ссылками на редактирование
 *
 * @param int    $id_w      Number of id
 * @param string $table     Char "l" or "a"
 * @param bool   $href      Bool If "true" See links
 * @param bool   $showPlace If "true" See place
 * @param bool   $showId    If "true" See id of person
 * @return String
 */
function list_leader_or_autors_str($id_w, $table, $href = false, $showPlace = false, $showId = false)
{
    global $link;
    global $FROM;

    $query = ($id_w != "") ? "SELECT * FROM `{$table}` WHERE id_w='{$id_w}'" : "SELECT * FROM `{$table}` ORDER BY `suname` ASC";
    //echo $query;
    //"SELECT * FROM `".$table."` ORDER BY `suname` ASC"
    $sub_table = ($table === 'wl') ? "leaders" : "autors";
	$linkName = ($table === 'wl') ? "leaders" : "authors";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query функція list_leader_or_autors_str: " . mysqli_error($link));
    // Попытка обработки строки ошибки когда база пуста
    //if('order clause'==mysqli_error($link)) return 0;
    $sub_row_str = "<ol>";
    while ($row = mysqli_fetch_array($result)) {
        $sub_row = fullinfo($sub_table, "id", $row[2]);
        $sub_row_str .= ($href)
            ? "<li title=\"Останні зміни: " . htmlspecialchars($sub_row['date']) . "\" >"
            : "<li title=\"{$sub_row['suname']} {$sub_row['name']} {$sub_row['lname']}\">";
        $sub_row_str .= ($href)
            ? "<a href=action.php?action=" . rtrim($linkName, "s") . "_edit&id_" . ltrim($table, "w") . "=" . $sub_row['id'] . "&FROM={$FROM} title=\"Ред.:{$sub_row['suname']} {$sub_row['name']} {$sub_row['lname']}\">"
            : "";
        $sub_row_str .= $sub_row['suname'] . " " . mb_substr($sub_row['name'], 0, 1, 'UTF-8') . "." . mb_substr($sub_row['lname'], 0, 1, 'UTF-8') . ".";
        $sub_row_str .= ($showId) ? "<{$sub_row['id']}>" : "";
        if ($showPlace && ($sub_row['place'] <> 'D')) {
            $sub_row_str .= "(&nbsp;{$sub_row['place']}&nbsp;)";
        }

        if ($sub_row['arrival'] == 1) {
            $sub_row_str .= "<span title=\"Прибув на конференцію\">&nbsp;[&radic;]&nbsp;</span>";
        }
        $sub_row_str .= ($href) ? "</a>" : "";
        if ($sub_row['arrival'] <> 1) {
            $sub_row_str .= ($href) ? " <a href=action.php?action=work_unlink&id_" . ltrim($table, "w") . "=" . $sub_row['id'] . "&id_w=" . $id_w . " title=\"Відокремити від роботи\"><img src=\"../images/unlink.png\"></a>" : "";
        }
        $sub_row_str .= "</li>\n";
    }
    $sub_row_str .= "</ol>\n";
    return $sub_row_str;
}