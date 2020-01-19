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
try {
    $apih = ApiHelper::getInstance();
    /** ApiActionsInterface $classObj */
    $classObj = $apih->getActionByName($action);
    if ($classObj instanceof ApiActionsInterface) {
        $classObj->init();
        echo $classObj->execute();
    }
} catch (\Exception $e) {
    if (isset(Base::$log) && Base::$log !== null) {
        Base::$log->error($e->getMessage());
    }
}


switch ($action) {
    case 'selwork':
        {
            if ($id_w !== false && $id_u !== false) {
                //Запросить кол. руководителей по работе
                $col_l = count_la('wl', $id_w);
                if ($col_l > 0) {
                    echo list_leader_or_autors_str($id_w, 'wl', true);
                } else {
                    echo '<span class="info">Відсутні</span>';
                }
                echo "!"; //символ разбиения строки не просто так написан
                if ($col_l < N_LEADERS) {
                    $i = 1;
                    while ($col_l < N_LEADERS) {
                        echo selectListAuthorsOrLeaders('leaders', 'leader[' . $i . ']', $id_u, 1) . "<br>";
                        $i++;
                        $col_l++;
                    }
                    $queryString = http_build_query(['action' => 'leader_add', 'id_u' => $id_u]);
                    echo "<a href='action.php?{$queryString}' class='btn' title=\"Внесення в базу даних керівника\">Створити</a>";
                } else {
                    echo '<span class="info">Досить</span>';
                }
                echo '!';//символ разбиения строки не просто так написан

                $col_a = count_la('wa', $id_w);
                if ($col_a > 0) {
                    echo list_leader_or_autors_str($id_w, 'wa', true);
                } else {
                    echo '<span class="info">Відсутні</span>';
                }
                echo '!';//символ разбиения строки не просто так написан
                if ($col_a < N_AUTORS) {
                    $i = 1;
                    while ($col_a < N_AUTORS) {
                        echo selectListAuthorsOrLeaders('autors', "autor[" . $i . "]", $id_u, 1) . '<br>';
                        $i++;
                        $col_a++;
                    }
                    $queryString = http_build_query(['action' => 'author_add', 'id_u' => $id_u, 'id_w' => $id_w]);
                    echo "<a href='action.php?{$queryString}' class='btn' title=\"Внесення в базу даних автора\">Створити</a>";
                } else {
                    echo '<span class="info">Досить</span>';
                }
            }
        }
        break;
    /**
     * Получить список всех руководителей и авторов данного вуза
     */
    //    case 'getlists':
    //        {
    //            list_fio('autors', 'autor', $_POST['id_u'], 1, $selecttag = false);
    //            echo '!';
    //            list_fio('leaders', 'leaders', $_POST['id_u'], 1, $selecttag = false);
    //        }
    //        break;
    //    case 'list_all':
    //        {
    //            list_fio('autors', 'autor', $_POST['id_u'], 1, $selecttag = true);
    //            echo "!";
    //            list_fio('leaders', 'leaders', $_POST['id_u'], 1, $selecttag = true);
    //        }
    //        break;
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

    $query = (!empty($id_w))
        ? "SELECT * FROM `{$table}` WHERE id_w='{$id_w}'" : "SELECT * FROM `{$table}` ORDER BY `suname` ";
    //echo $query;
    //"SELECT * FROM `".$table."` ORDER BY `suname` ASC"
    $sub_table = ($table === 'wl') ? 'leaders' : 'autors';
    $linkName = ($table === 'wl') ? 'leaders' : 'authors';
    $result = mysqli_query($link, $query)
    or die("Invalid query функція list_leader_or_autors_str: " . mysqli_error($link));
    // Попытка обработки строки ошибки когда база пуста
    //if('order clause'==mysqli_error($link)) return 0;
    $sub_row_str = '<ol>';
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
        if ($showPlace && $sub_row['place'] <> 'D') {
            $sub_row_str .= "(&nbsp;{$sub_row['place']}&nbsp;)";
        }

        if ($sub_row['arrival'] == 1) {
            $sub_row_str .= "<span title=\"Прибув на конференцію\">&nbsp;[&radic;]&nbsp;</span>";
        }
        $sub_row_str .= ($href) ? "</a>" : "";
        if ($sub_row['arrival'] <> 1) {
            $sub_row_str .= ($href) ? " <a href=action.php?action=work_unlink&id_" . ltrim($table, "w") . "=" . $sub_row['id'] . "&id_w=" . $id_w . " title=\"Відокремити від роботи\"><img src=\"../images/unlink.png\"></a>" : "";
        }
        $sub_row_str .= "</li>";
    }
    $sub_row_str .= "</ol>";
    return $sub_row_str;
}