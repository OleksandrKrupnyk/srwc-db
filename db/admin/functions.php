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
 * @throws Exception
 */
function list_files(int $id_w, MysqliDb $db, string $typeoffile = 'all'): string
{
    if (!empty($id_w)) {
        $query = "SELECT * FROM `files` WHERE `id_w`='$id_w'";
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
                    "action.php?" . http_build_query([
                        'action' => 'file_get',
                        'guid' => $row['guid']
                    ]), [
                    'class' => "link-file",
                    'title' => $fullFileName,
                    'data-ext' => $fileExtension,
                    'style' => "margin-right:10px"
                ])
                . Html::a('', "action.php?" . http_build_query([
                        'action' => 'file_remove',
                        'id_w' => $id_w,
                        'guid' => $row['guid']
                    ]), [
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
 * @return string
 * @throws Exception
 */
function list_emails(string $table, MysqliDb $db): string
{
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
        $get_text = "a";
    } elseif ($table === 'leaders') {
        $query = "SELECT works.title,leaders.id, leaders.suname, leaders.name,leaders.lname,leaders.hash,leaders.email,leaders.email_recive,leaders.email_date
                 FROM works
                 LEFT JOIN wl ON wl.id_w = works.id
                 LEFT JOIN leaders ON wl.id_l = leaders.id
                 WHERE works.invitation = '1'
                 ORDER BY leaders.suname ";
        $get_text = "l";
    }
    $results = $db->rawQuery($query);

    $listEmails = [];
    $listNoEmails = [];
    foreach ($results as $row) {
        $name = $row['suname'] . " " . $row['name'] . " " . $row['lname'];
        $content = [];
        if (!empty($row['email'])) {
            $content[] = $name;
            $content[] = Html::a(
                'Получить письмо!',
                'getmails.php?' . http_build_query(['hash' => $row['hash'], 't' => $get_text])
            );
            if ((string)$row['email_recive'] === '1') {
                $content[] = " [The email have received and read." . $row['email_date'] . " ] ";
            }
            $content[] = Html::hiddenInput('emails[]', $row['email']);
            $content[] = Html::hiddenInput('whom[]', $name);
            $content[] = Html::hiddenInput('hashs[]', $row['hash']);
            $content[] = Html::hiddenInput('titles[]', $row['title']);
            $listEmails [] = Html::tag('li', implode('', $content));
        } else {
            $listNoEmails [] = Html::tag('li', $name . " <mark>Поштова скринька відсутня!</mark>");
        }
    }
    $listEmails = implode('', $listEmails);
    $listNoEmails = implode('', $listNoEmails);
    return <<<__HTML__
<details><summary>Список отримувачів</summary><ol name="{$table}">$listEmails</ol></details>
<details><summary>Не надали адресу</summary><ol>$listNoEmails</ol></details>
__HTML__;
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
 * @throws Exception
 */
function getListOfObjects(
    MysqliDb $db,
    string $object,
    bool $phone = false,
    bool $email = false,
    bool $hash = false,
    bool $onlyReviwers = false
): string
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
    $list = [];
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
            if ((string)$row['email_recive'] === '1' && $hash) {
                $sub_row_str .= ' [The email have received and read.' . $row['email_date'] . ' ] ';
            }
            $sub_row_str .= Html::a('',
                "lists.php?" . http_build_query(['action' => "badge_{$object}s", 'badge' => $row['id']]),
                ['title' => 'Друкувати посвідчення(тільки зв\'язані з роботою)']
            );
            $sub_row_str .= Html::checkbox('works_id[]', false, ['value' => $row['id']]);
        }
        $list [] = Html::tag('li', $sub_row_str, [
            'title' => 'Останні зміни :' . htmlspecialchars($row['date']),
            'data-index' => $row['id']
        ]);
    }
    $items = implode('', $list);
    return <<<__HTML__
<ol data-object-name="$object">$items</ol>
__HTML__;
}


/**
 * @param MysqliDb|null $db
 * @return string
 * @throws Exception
 */
function getListLeadersWhoArrival(MysqliDb $db): string
{
    $query = "
SELECT CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as fio, 
       degreefull, 
       statusfull, 
       position,
       univerrod 
FROM `leaders`
      LEFT JOIN positions ON positions.id = leaders.id_pos 
      LEFT JOIN univers ON univers.id = leaders.id_u
      LEFT JOIN degrees ON degrees.id = leaders.id_deg
      LEFT JOIN statuses ON statuses.id = leaders.id_sat
  WHERE arrival='1' ORDER BY fio;";

    $results = $db->rawQuery($query);
    $list = implode('<br/>', array_map(static function ($item) {
        return vsprintf("%s, %s, %s, %s %s", $item);
    }, $results));
    return <<<__HTML__
<h3> </h3>
<details>
<summary>Список супроводжуючих/керівників, що прибули для участі у роботі журі (Розгорнутий)</summary>$list
</details>
__HTML__;
}

/**
 * @param MysqliDb $db
 * @return string
 * @throws Exception
 */
function getShortListLeadersWhoArrival(MysqliDb $db): string
{
    $query = "
SELECT CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as  fio 
FROM `leaders` 
WHERE `arrival`='1' 
ORDER BY fio;
";
    $results = $db->rawQuery($query);
    $list = implode('<br/>', array_map(static function ($item) {
        return $item['fio'];
    }, $results));
    return <<<__HTML__
<h3> </h3>
<details>
<summary>Список супроводжуючих/керівників, що прибули для участі у роботі журі</summary>$list
</details>
__HTML__;
}

/**
 * Список авторов без нумерации
 *
 *  Фамилия и инициалы
 *
 * @param MysqliDb $db
 * @param int $id_w
 * @return string
 * @throws Exception
 */
function getShortListAutors(MysqliDb $db, int $id_w):string
{
    $query = "
SELECT autors.id, 
        CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as fio 
FROM `wa` 
    left outer join `autors` ON `wa`.`id_a`=`autors`.`id` 
WHERE `id_w`='" . $id_w . "' ORDER BY `fio`;";
    $results = $db->rawQuery($query);
    return implode('<br/>', array_map(static function ($item) {
        return $item['fio'];
    }, $results));
}


/**
 * @param string|null $page
 */
function Go_page(?string $page):void
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