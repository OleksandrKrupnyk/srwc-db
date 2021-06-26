<?php

/**
 * @author studyjquery
 * @copyright 2014
 */

use zukr\base\helpers\StringHelper;
use zukr\base\html\Html;

/**
 * список файлов работы
 *
 * @param int $id_w
 * @param MysqliDb $db
 * @param string $typeoffile
 * @return String
 */
function list_files($id_w, MysqliDb $db, string $typeoffile = 'all')
{
    if (!empty($id_w)) {
        $query = "SELECT * FROM `files` WHERE `id_w`='{$id_w}'";
        switch ($typeoffile) {
            case '0':
                {
                    $query .= " AND `typeoffile` = '0'";
                }
                break;
            case '1':
                {
                    $query .= " AND `typeoffile` = '1'";
                }
                break;
            case '2':
                {
                    $query .= " AND `typeoffile` = '2'";
                }
                break;
            case 'all' :
            default:
                {
                }
                break;
        }
        $results = $db->rawQuery($query);

        $list = [];
        foreach ($results as $row) {
            $fullFileName = basename($row['file']);
            $fileNameParts = explode('.', $fullFileName);
            $fileExtension = end($fileNameParts);
            //
            $truncateFileName = StringHelper::truncate($fullFileName, 30);
            //
            $list[] = Html::a(
                    $truncateFileName,
                    "action.php?action=file_get&guid={$row['guid']}", [
                    'class' => "link-file",
                    'title' => $fullFileName,
                    'data-ext' => $fileExtension,
                    'style' => "margin-right:10px"
                ])
                . Html::a('', "action.php?action=file_remove&id_w={$id_w}&guid={$row['guid']}", [
                    'title' => 'Видалити файл',
                    'class' => 'link-delete-file'
                ]);
        }
        $str = count($results) > 0
            ? '<details><summary>Файли</summary>' . Html::ol($list) . '</details>'
            : '<div><mark>Нема файлів для відображення</mark></div>';
    } else {
        $str = '<mark>Нема файлів для відображення</mark>';
    }
    return $str;
}


/**
 * @param string $table
 * @param MysqliDb|null $db
 */
function list_emails($table, MysqliDb $db = null)
{
    global $link;
    $query = '';
    $get_text = '';
    //Формируем запрос на получение таблицы
    if ($table === 'autors') {//авторы работ
        $query = "SELECT works.title,autors.id, autors.suname, autors.name,autors.lname,autors.hash,autors.email,autors.email_recive,autors.email_date
                 FROM works
                 LEFT JOIN wa ON wa.id_w = works.id
                 LEFT JOIN autors ON wa.id_a = autors.id
                 WHERE works.invitation = '1'
                 ORDER BY autors.suname";
        $get_text = "&t=a";
    } elseif ($table === 'leaders') {
        $query = "SELECT works.title,leaders.id, leaders.suname, leaders.name,leaders.lname,leaders.hash,leaders.email,leaders.email_recive,leaders.email_date
                 FROM works
                 LEFT JOIN wl ON wl.id_w = works.id
                 LEFT JOIN leaders ON wl.id_l = leaders.id
                 WHERE works.invitation = '1'
                 ORDER BY leaders.suname ";
        $get_text = "&t=l";
    }

    //echo $query."\n";
    $result = mysqli_query($link, $query)
    or die("Invalid query функція list_emails: " . mysqli_error($link));
    $sub_row_str_nomail = "<details><summary>Не надали адресу</summary><ol>";
    $sub_row_str = "<details><summary>Список отримувачів</summary><ol name=" . $table . ">";
    while ($row = mysqli_fetch_array($result)) {


        if ($row['email'] != "") {
            $sub_row_str .= "<li>" . $row['suname'] . " " . $row['name'] . " " . $row['lname'];
            $sub_row_str .= "<a href=\"getmails.php?hash=" . $row['hash'] . $get_text . "\">Получить письмо!</a>";
            if ($row['email_recive'] == 1) {
                $sub_row_str .= " [The email have recived and read." . $row['email_date'] . " ] ";
            }
            $sub_row_str .= "<input type=\"hidden\" name=emails[] value =" . $row['email'] . " >";
            $sub_row_str .= "<input type=\"hidden\" name=whom[]   value =\"" . $row['suname'] . " " . $row['name'] . " " . $row['lname'] . "\" >";
            $sub_row_str .= "<input type=\"hidden\" name=hashs[]  value =\"" . $row['hash'] . "\">";
            $sub_row_str .= "<input type=\"hidden\" name=titles[] value =\"" . $row['title'] . "\">";
            $sub_row_str .= "</li>\n";
        } else {
            $sub_row_str_nomail .= "<li>" . $row['suname'] . " " . $row['name'] . " " . $row['lname'] . " <mark>Поштова скринька відсутня!</mark>";
        }

        //print_r($row);

    }
    $sub_row_str_nomail .= "</ol>\n</details>";
    $sub_row_str .= "</ol>\n</details>\n" . $sub_row_str_nomail;
    return $sub_row_str;
}


/**
 * Список авторов или руководителей
 *
 * @param MysqliDb $db
 * @param string $object 'author' or 'leader'
 * @param bool $phone
 * @param bool $email
 * @param bool $hash
 * @param bool $onlyReviwers
 * @return string
 */
function getListOfObjects(
    MysqliDb $db,
    string $object,
    bool $phone = false,
    bool $email = false,
    bool $hash = false,
    bool $onlyReviwers = false
)
{
    if (empty($object) || !in_array($object, ['author', 'leader'])) {
        return '';
    }

    $id = ($object === 'author') ? 'id_a' : 'id_l';
    //."JOIN `positions` ON `leaders`.`id_pos` = `positions`.`id`\n
    if ($object === 'author') {
        $query = "SELECT autors.*, univers.univer 
                  FROM `autors` 
                      JOIN univers ON autors.id_u = univers.id 
                  ORDER BY `suname` ";
    } else {
        $query = "SELECT leaders.*, positions.position,degrees.degree,statuses.status,univers.univer 
FROM leaders 
JOIN positions ON leaders.id_pos = positions.id
JOIN degrees ON leaders.id_deg = degrees.id
JOIN statuses ON leaders.id_sat = statuses.id
JOIN univers ON leaders.id_u = univers.id";
        $query .= $onlyReviwers === true ? " WHERE leaders.review = '1' " : '';
        $query .= ' ORDER BY `suname` ASC';
    }

    $results = $db->rawQuery($query);
    foreach ($results as $row) {
        $sub_row_str = Html::a(
            $row['suname'] . " " . $row['name'] . " " . $row['lname'],
            "action.php?" . http_build_query(['action' => "{$object}_edit", $id => $row['id']]),
            [
                'title' => 'Ред.' . $row['univer'],
            ]);
        if (!$onlyReviwers && (int)$row['arrival'] === 0) {
            $sub_row_str .= Html::a('', '#', ['title' => 'Видалити з реестру', 'class' => "js-delete-list-item"]);
        }
        $sub_row_str .= (int)$row['arrival'] === 1
            ? Html::tag('span', '&nbsp;[&radic;]&nbsp;', ['title' => 'Прибув на конференцію'])
            : '';

        $get_text = '&t=a'; // для GET запроса в хеш сторку
        if ($object === 'leaders') {
            $sub_row_str .= ' ' . $row['position'];
            $sub_row_str .= ' ' . $row['degree'];
            $sub_row_str .= ' ' . $row['status'];
            $get_text = '&t=l'; // для GET запроса в хеш сторку
        }

        if (!$onlyReviwers) {
            $phone_number = ($row['phone'] !== '') && $phone ? $row['phone'] : 'відсутній';
            $email_text = ($row['email'] !== '') && $email
                ? Html::tag('strong', 'e-mail:') . Html::mailto($row['email'], $row['email'])
                : '';

            $sub_row_str .= $phone ? Html::tag('span', $phone_number, ['id' => "phone"]) : '';
            $sub_row_str .= '' . $email_text;


            $sub_row_str .= $hash
                ? Html::a('Получить письмо!', 'getmails.php?hash=' . $row['hash'] . $get_text)
                : '';
            if ($row['email_recive'] == 1 && $hash) {
                $sub_row_str .= ' [The email have received and read.' . $row['email_date'] . ' ] ';
            }
            $sub_row_str .= Html::a('',
                "lists.php?" . http_build_query(['action' => "badge_{$object}s", 'badge' => $row['id']]),
                ['title' => 'Друкувати посвідчення(тільки зв\'язані з роботою)']
            );
            $sub_row_str .= Html::checkbox('works_id[]', false, ['value' => $row['id']]);
        }
        $list [] = Html::tag('li', $sub_row_str, [
            'title' => 'Останні зміни :' . \htmlspecialchars($row['date']),
            'data-index' => $row['id']
        ]);
    }
    $items = implode('', $list);
    return <<<__HTML__
<ol data-object-name="{$object}">{$items}</ol>
__HTML__;
}


/**
 * @param bool $showAllInfo
 * @param MysqliDb|null $db
 */
function listLeadersWhoArrival($showAllInfo = false, MysqliDb $db = null)
{
    global $link;
    $query = "SELECT CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as  fio FROM leaders WHERE arrival='1' ORDER BY fio ASC";
    $text = "";
    $rowTable = "%s<br>";
    if ($showAllInfo) {
        $query = "SELECT CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as fio, degreefull, statusfull, position,univerrod FROM leaders
                  LEFT JOIN positions ON positions.id = leaders.id_pos 
                  LEFT JOIN univers ON univers.id = leaders.id_u
                  LEFT JOIN degrees ON degrees.id = leaders.id_deg
                  LEFT JOIN statuses ON statuses.id = leaders.id_sat
                  WHERE arrival='1' ORDER BY fio ASC";
        $text = '(Розгорнутий)';
        $rowTable = "%s, %s, %s, %s %s<br>";

    }
    $result = mysqli_query($link, $query)
    or die('Помилка запиту функція listLeadersWhoArrival: ' . mysqli_error($link));
    echo "<h3> </h3>";
    echo "<details><summary>Список супроводжуючих/керівників, що прибули для участі у роботі журі {$text}</summary>";
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        //print_r($row);
        echo vsprintf($rowTable, $row);
    }
    echo "</details>";
}

/**
 * Список руководителей или авторов без нумерации Используцется при формировании программы
 *  Фамилия и инициалы
 *
 * @param      $id_w
 * @param      $who
 * @param bool $showId
 * @param MysqliDb|null $db
 */
function short_list_leader_or_autors_str($id_w, $who, $showId = false, MysqliDb $db = null)
{
    global $link;
    $query = ($who === 'wa') ?
        "SELECT autors.id, 
        CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as fio\n 
        FROM `wa` left outer join `autors` ON `wa`.`id_a`=`autors`.`id`\n" :
        "SELECT leaders.id, CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as  fio,
        `position`,`status`,`degree`FROM `wl` 
        LEFT OUTER JOIN `leaders` ON `wl`.`id_l`=`leaders`.`id`
        LEFT OUTER JOIN `positions` ON `leaders`.`id_pos` = `positions`.`id`
        LEFT OUTER JOIN `statuses` ON `leaders`.`id_sat` = `statuses`.`id`
        LEFT OUTER JOIN `degrees` ON `leaders`.`id_deg` = `degrees`.`id`";


    $query .= "WHERE `id_w`='" . $id_w . "' ORDER BY `fio` ASC";
    //echo $query;

    $result = mysqli_query($link, $query)
    or die('Помилка запиту функція short_list_leader_or_autors_str: ' . mysqli_error($link));
    while ($row = mysqli_fetch_array($result)) {
        //print_r($row);
        if ($who === 'wa') {
            $str = $row['fio'];
            $str .= ($showId == true) ? "<span id=\"id\" >(№" . $row['id'] . ")</span>" : "";
        } else {
            $str = '';
            if ($row['degree'] !== '-немає-') {
                $str .= $row['degree'];
                if ($row['status'] !== '-немає-') {
                    $str .= ', ' . $row['status'] . ",<br/> " . $row['fio'];
                } else {
                    $str .= ",<br/> " . $row['fio'];
                }
            } else {
                $str .= $row['position'] . ",<br/> " . $row['fio'];
            }
        }
        $str .= "<br>";
        echo $str;
    }
}


/**
 * @param string $page
 */
function Go_page($page)
{
    $page = ($page === 'error' || $page === null) ? 'action.php?action=error_list' : $page;
    header('location: ' . urldecode($page));
    exit();
}

/**
 * @param string $action_string
 */
function execute_get_action(string $action_string)
{
    if (is_string($action_string)) {
        $action = explode('_', $action_string);
        if (count($action) === 2) {
            $directory = $action[0];
            $path[] = $directory;
            $fileName = 'form_' . $action[1] . '.php';
            $path[] = $fileName;
            $file = implode(DIRECTORY_SEPARATOR, $path);
            if (is_file($file)) {
                include_once $file;
            }
        }
    }
}

/**
 * @param string $action_string
 */
function execute_post_action(string $action_string)
{
    if (is_string($action_string)) {
        $action = explode('_', $action_string);
        if (count($action) === 2) {
            $directory = $action[0];
            $path[] = $directory;
            $fileName = $action[1] . '.php';
            $path[] = $fileName;
            $file = implode(DIRECTORY_SEPARATOR, $path);
            if (is_file($file)) {
                include_once $file;
            }
        }
    }
}

/**
 * @param string $action_string
 */
function execute_print_action(string $action_string)
{
    if (is_string($action_string)) {
        $path = ['documents', $action_string . '.php'];
        $file = implode(DIRECTORY_SEPARATOR, $path);
        if (is_file($file)) {
            include_once $file;
        }
    }
}