<?php

/**
 * @author studyjquery
 * @copyright 2014
 */

use zukr\author\AuthorHelper;
use zukr\base\Base;
use zukr\base\html\HtmlHelper;
use zukr\base\LoginUser;
use zukr\file\FileHelper;
use zukr\leader\LeaderHelper;
use zukr\review\ReviewHelper;
use zukr\work\WorkHelper;

/**
 * Функция считытывания настроек програмы из таблици settings БД
 *
 */
function read_settings()
{
    global $link;
//Доступ к переменным масива из вне
    global $settings;
    $query = 'SELECT * FROM `settings`';
    $result = mysqli_query($link, $query)
    or die('Помилка зчитування налаштувань програми: ' . mysqli_error($link));
    while ($row = mysqli_fetch_array($result)) {
        $parametr = $row['parametr'];
        if ('1' === $row['value'])
            $settings[$parametr] = '1';
    }
}
/**
 * Функция журналирования системы
 * @param string $action
 * @param string $table
 * @param int $action_id
 * @param int $tz_id
 */
function log_action($action, $table, $action_id = 0, $tz_id = 888)
{
    global $link;
    //Проверка а доступен ли tz_id
    $action_id = (int)isset($action_id) ? $action_id : 777;
    $action_id = (int)$action_id = '' ? 777 : $action_id;
    $tz_id = $_SESSION['id'] ?? $tz_id;
    $query = "INSERT INTO `log` (`tz_id`,`date`,`action`,`table`,`action_id`,`tz_ip`)\n
        VALUES\n
        ('{$tz_id}',NOW(),'{$action}','{$table}','{$action_id}','{$_SERVER['REMOTE_ADDR']}')";
    //echo( $query);
    $result = mysqli_query($link, $query)
    or die('Помилка запису журналу: ' . mysqli_error($link) . $action_id);
}

/**
 *  Список университетов всех которые есть
 *  Возвращает значение в переменной id_u (тип integer)
 * @param int $chk  номер вуза
 * @param int $size Размер списка
 * @param bool $invite
 * @param bool $shortname
 * @param bool $checkin
 *  list_univers(<номер отмеченого вуза>,<размер списка>,<1-отображать только из 1ИнфСооб>)
 * */
function list_univers($chk, $size, $invite = true,$shortname = false, $checkin=false):string
{
    global $link;
    $query = "SELECT `univers`.*  FROM univers ";
    $query .= ($checkin ==1)?" RIGHT JOIN works ON works.id_u=univers.id ":"";
    $query .= ($invite == 1)?" WHERE `invite`= 1  OR `univers`.`id` = 1 " : ""; //`id` = 1 - это ВУЗ ДДТУ
    $query .= ($checkin ==1)?" GROUP BY univers.id ":"";
    $query .= "ORDER BY univer";
    //echo $query;
    $result = mysqli_query($link, $query)
    or die("Invalid query: " . mysqli_error($link));
    $idString = ($shortname == 0)? "selunivers":"shortlistunivers";

    $str =  "<select id=\"{$idString}\" name=\"id_u\" size=\"{$size}\" required class='w-100'><option value=\"-1\" disabled selected>Університет...</option>\n";
    while ($row = mysqli_fetch_array($result)) {
        $NameUniver  = ($shortname == 0)? "{$row['univer']}({$row['univerfull'] })" : "{$row['univer']}";
        if (isset($chk) && $chk != "" && $row['id'] == $chk) {
            $str .= "<option value=\"{$row['id']}\" selected>{$NameUniver}</option>\n";
        } else {
            if (isset($_COOKIE['cid_u']) && $row['id'] == $_COOKIE['cid_u'] && $chk == "") {
                $str.= "<option value=\"{$row['id']}\" selected>{$NameUniver}</option>\n";
            } else
                $str.= "<option value=\"{$row['id'] }\">{$NameUniver}</option>\n";
        }
    }
    $str .= "</select>\n";
    return $str;
}

/**
 * @param int  $id_w ID of work in table works
 * @param bool $href TRUE if you need to show link for edit
 * @param bool $isAdmin
 * @param int  $userProfileId
 * @return string Numeric list of reviews with links
 */
function list_reviews_for_one_work($id_w, bool $href = false, $isAdmin = false,$loginId=0)
{
    global $link;
    $query = "SELECT `reviews`.`id`, `reviews`.`id_w`, leaders.id_tzmember, reviews.conclusion,
              CONCAT(`leaders`.`suname`,' ',`leaders`.`name`,' ',`leaders`.`lname`) AS fio,
              (`actual`+`original`+`methods`+`theoretical`+`practical`+`literature`+`selfcontained`+`design`+`publication`+`government`+`tendentious`) AS sumball 
              FROM `reviews` 
              JOIN `leaders` ON `leaders`.`id` = `reviews`.`review1` 
              WHERE id_w = {$id_w}";

    $result = mysqli_query($link, $query)
    or die('Invalid query in function list_reviews_for_one_work : ' . mysqli_error($link));
    $fullSum = 0;
    $conclusions = '';
    $str = '<ol>';
    while ($row = mysqli_fetch_array($result)) {
        $fullSum += $row['sumball'];
        $item = '';
        if ($href) {
            $item .= "<a href=\"action.php?action=review_edit&id={$row['id']}\" title=\"Ред. Рец.:{$row['fio']}\">&#9998;:[{$row['sumball']}]</a>\n";
            if ($loginId == $row['id_tzmember'] || $isAdmin) {
                $item .= "<a href=\"action.php?action=review_delete&id={$row['id']}&id_w={$row['id_w']}\"  title=\"Видалити рецензію\"></a>\n";
            }
        } else {
            $item .= "<a href=\"action.php?action=review_view&id={$row['id']}\" title=\"[{$row['sumball']}]\">Реценз.</a>\n";
        }
        $conclusions .= ($row['conclusion'] == 1) ? 'ТАК&nbsp;' : 'НІ&nbsp;';

        $str .= '<li>' . $item . '</li>';
    }

    $str .= '</ol>';

    $str .= "<p>&nbsp;&nbsp;&nbsp;<strong>&sum;:{$fullSum}</strong>&nbsp;{$conclusions}</p>";
    return $str;
}


/**
 *
 * Список [Фамилия Имя Отчество]
 *  <select></select>
 *
 * @param string $table Таблица
 * @param string $pole  Имя параметра name
 * @param int    $id_u  id of Univercity
 * @param int    $size  Size of list
 * @return string
 */
function selectListAuthorsOrLeaders(string $table, string $pole, int $id_u, int $size): string
{
    global $link;

    $query = ($table === 'leaders')
        ? 'SELECT * FROM `leaders` '
        : 'SELECT * FROM `autors` ';

    if (!empty($id_u)) {
        $query .= " WHERE `id_u`='{$id_u}' ";
    }
    $query .= ' ORDER BY  `suname`';
    $result = mysqli_query($link, $query)
    or die('Invalid query: ' . mysqli_error($link));
    //Проверка а вообще есть результаты по запросу
    $count = mysqli_num_rows($result);
    if ($count === 0) {
        return 'Незнайдено';
    }
    $items = [];
    while ($row = mysqli_fetch_array($result)) {
        $items[] = "<option value='{$row['id']}' >" . implode(' ', [$row['suname'], $row['name'], $row['lname']]) . "</option>";
    }
    return "<select size='{$size}' name='{$pole}' class='w-100'><option value='-1' selected disabled>Оберіть...</option>"
        . implode('', $items)
        . '</select>';

}
/**
 * Возвращает список руководителей с выбором включать в лист приглашения
 *
 * @param Integer $id_u
 * @param Boolean $check
 */
function list_leaders_invite($id_u, $check = true)
{
    global $link;
    $query = 'SELECT `leaders`.*,`positions`.`position`  FROM `leaders`'
        . 'JOIN `positions` ON `leaders`.`id_pos` = `positions`.`id`'
        . "WHERE `leaders`.`id_u`='" . $id_u . "' ";
    $query .= ($check) ? '' : ' AND `leaders`.`invitation` = TRUE ';
    $query .= "ORDER BY  `suname` ASC";

    $result = mysqli_query($link, $query) or die('Invalid query: ' . mysqli_error($link));
    $count = mysqli_num_rows($result);
    if ($count == 0) {
        return false;
    }
    echo '<ol>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<li>' . $row['suname'] . ' ' . $row['name'] . ' ' . $row['lname'] . ', ' . $row['position'] . '</li>';
    }
    echo '</ol>';
}


/**
 * Данные все данные по 1 полю в таблице
 *
 * @param string $table
 * @param string $field
 * @param string $value
 * @return array
 */
function fullinfo(string $table, string $field, $value): ?array
{
    global $link;
    $query = "SELECT * FROM `{$table}` WHERE `{$field }`='{$value}'";
    $result = mysqli_query($link, $query)
    or die('Помилка запиту функція fullinfo: ' . mysqli_error($link));
    return mysqli_fetch_array($result);
}

/**
 * список файлов работы
 *
 * @param int $id_w
 * @param string $typeoffile
 * @return String
 */
function list_files($id_w, string $typeoffile = 'all')
{
    global $link;
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

        $result = mysqli_query($link, $query)
        or die('Помилка запиту функція list_files: ' . mysqli_error($link));
        $count = mysqli_num_rows($result);
        if ($count != 0) {
            $str = "<details><summary>Файли</summary><ol>";
            while ($row = mysqli_fetch_array($result)) {
                //$str2=explode("/",$row['file']);
                //$str2=end($str2);
                $str_title = basename($row['file']);
                //
                $str2 = file_name_format($str_title, 30);
                //
                $str .= "<li><a href=\"{$row['file']}\" class='link-file' title=\"{$str_title}\" >{$str2}</a>&nbsp;"
                    . "<a href=\"action.php?action=file_delete&id_w={$id_w}&id_f={$row['id']}\" title=\"Видалити файл\"></a></li>";
                unset($str2);
            }
            $str .= "</ol></details>";
        } else
            $str = '';
    } else {
        $str = '<mark>Нема файлів для відображення</mark>';
    }
    return $str;
}

/**
 * Выдает количество авторов (руководителей) у работы
 *
 * @param string $table Table in MySQL
 * @param int    $id_w  id Work
 * @return int
 */
function count_la(string $table, int $id_w)
{
    global $link;
    $query = ($table === 'wl')
        ? "SELECT COUNT(*) FROM `wl` WHERE `id_w`='" . $id_w . "'"
        : "SELECT COUNT(*) FROM `wa` WHERE `id_w`='" . $id_w . "'";
    $result = mysqli_query($link, $query)
    or die('Помилка запиту функція count_la: ' . mysqli_error($link));
    $row = mysqli_fetch_array($result);
    return (int)$row[0];
}

/**
* @param string $table
*/
function list_emails($table)
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
    }
    elseif ($table === 'leaders'){
        $query= "SELECT works.title,leaders.id, leaders.suname, leaders.name,leaders.lname,leaders.hash,leaders.email,leaders.email_recive,leaders.email_date
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
             $sub_row_str .="<input type=\"hidden\" name=titles[] value =\"".$row['title']."\">";
             $sub_row_str .= "</li>\n";
         }
         else
            {$sub_row_str_nomail.= "<li>".$row['suname']." ".$row['name']." ".$row['lname']." <mark>Поштова скринька відсутня!</mark>";}

        //print_r($row);

        }
    $sub_row_str_nomail .="</ol>\n</details>";
    $sub_row_str .= "</ol>\n</details>\n".$sub_row_str_nomail;
    return $sub_row_str;
}


/**
 * Список авторов или руководителей
 *
 * @param string $object 'author' or 'leader'
 * @param bool   $phone
 * @param bool   $email
 * @param bool   $hash
 * @param bool   $onlyReviwers
 * @return string
 */
function getListOfObjects(string $object, bool $phone = false, bool $email = false, bool $hash = false, bool $onlyReviwers = false)
{
    global $link;
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
        $query .= $onlyReviwers == true ? " WHERE leaders.review = '1' " : '';
        $query .= ' ORDER BY `suname` ASC';
    }

    $result = mysqli_query($link, $query)
    or die('Invalid query функція list_autors_or_leaders: ' . mysqli_error($link));
    $sub_row_str = '<ol data-object-name=' . $object . '>';
    while ($row = mysqli_fetch_array($result)) {
        //print_r($row);
        $sub_row_str .= '<li data-index=' . $row['id'] . ' title="Останні зміни :' . htmlspecialchars($row['date']) . '">'
            . "<a href=action.php?action=" . rtrim($object, "s") . '_edit&' . $id . '=' . $row['id'] . "  title=\"Ред.{$row['univer']}\">"
            . $row['suname'] . " " . $row['name'] . " " . $row['lname'] . '</a>  ';
        if (!$onlyReviwers && (int)$row['arrival'] === 0) {
            $sub_row_str .= '<a href="#" title="Видалити з реестру" class="delete-author"></a>';
        }
        $sub_row_str .= (int)$row['arrival'] === 1 ? '<span title="Прибув на конференцію">&nbsp;[&radic;]&nbsp;</span>' : '';

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
                ? '<strong>e-mail:</strong><a href="mailto:' . $row['email'] . '">' . $row['email'] . '</a>'
                : '';
            $sub_row_str .= $phone ? '<span id="phone">' . $phone_number . '</span>' : '';
            $sub_row_str .= '' . $email_text;


            $sub_row_str .= $hash
                ? '<a href="getmails.php?hash=' . $row['hash'] . $get_text . '">Получить письмо!</a>'
                : '';
            if ($row['email_recive'] == 1 && $hash) {
                $sub_row_str .= ' [The email have received and read.' . $row['email_date'] . ' ] ';
            }
            $sub_row_str .= "<a href=\"lists.php?action=badge_{$object}s&badge={$row['id']}\" title=\"Друкувати посвідчення(тільки зв'язані з роботою)\"></a>";
            $sub_row_str .= '<input type="checkbox" name="works_id[]" value="' . $row['id'] . '">';
            $sub_row_str .= "</li>\n";
        }
    }
    $sub_row_str .= '</ol>';
    return $sub_row_str;
}


/**
 * Выводит заголовок  "название университета" в таблице посмотра данных о работах
 *
 * @param string $univer_title
 * @param int $id_u
 * @param string $univer
 * */
function print_work_univer($univer_title, $id_u, $univer)
{
    $FROM = $_SESSION['from'] ?? '';
    return '
    <tr>
    <td colspan="5" class="univerTitle">
    <div id=id_u' . $id_u . ' style="display:inline;margin-right:5px">' . $univer . '</div>
    <a href="action.php?action=univer_edit&id_u=' . $id_u . '&FROM=' . $FROM . '" title="Редагувати данні університету">' . $univer_title . '</a>
    </td>
    </tr>';
}

/**
 * Выводит рядок в таблице просмотра данных о работе
 *
 * @param array  $work
 * @param LoginUser $userLogin
 **/
function print_work_row(array $work,LoginUser $userLogin)
{
    $wh = WorkHelper::getInstance();
    $sections = $wh->getAllSections();
    $section = $sections[$work['id_sec']]['section'] ?? '';

    $ah = AuthorHelper::getInstance();
    $autors = $ah->getAutorsByWorkId($work['id']);

    $lh = LeaderHelper::getInstance();
    $leaders = $lh->getLeadersByWorkId($work['id']);

    $fh = FileHelper::getInstance();
    $filesOfWork = $fh->getFilesOneWork($work['id']);
    $rh = ReviewHelper::getInstance();
    /** @var \zukr\base\AuthInterface $user */
    $user =$userLogin->getUser();

    $title = '<a href=action.php?action=work_edit&id_w=' . $work['id'] . ' title="Редагувати роботу" class="">';
    $title .= $work['arrival'] == '1'
        ? $work['title'] . '&nbsp;[&radic;]&nbsp;'
        : $work['title'];
    $title .= "</a>\n";

    $invitateClassWork = $work['invitation'] == 1
        ? 'invitateWork'
        : '';

    $tesis = $work['tesis'] == 0 ? '' : "<strong>З тезами</strong>\n";

    $list_leaders = WorkHelper::leaderList($leaders, false);

    $list_autors = WorkHelper::authorList($autors, true, true);

    $date = $work['date'];

    //если установлено показывать ссылки
    $link_add_review = (
        (
            ($user->isReview() && (int)Base::$param->DENNY_EDIT_REVIEW===Base::KEY_OFF )
            ||
            $user->isAdmin()
        )
        && (int)$rh->getCountOfReviewByWorkId($work['id']) < 2
    )
        ? '<a href="action.php?action=review_add&id_w=' . $work['id'] . '&id_u=' . $work['id_u'] . '">додати рецензію</a>'
        : '';

    $reviews = list_reviews_for_one_work(
        $work['id'], ($user->isReview() && (int)Base::$param->DENNY_EDIT_REVIEW === Base::KEY_OFF) || $user->isAdmin(),
        $user->isAdmin(),
        $userLogin->getId());

    $moto = '<br/><strong>Шифр</strong>:' . $work['motto'] . PHP_EOL;
    $link_work = '<a href=action.php?action=work_link&id_w='
        . $work['id']
        . ' title="Звязати з роботою керівника/автора">&laquo;</a>&nbsp;&nbsp;' .PHP_EOL;
    $introduction = !empty($work['introduction'])
        ? '<br/><strong>Впровадження</strong>:' . $work['introduction'] . PHP_EOL
        : '';
    $public = !empty($work['public'])
        ? "<br/><strong>Публікації</strong> :{$work['public']}".PHP_EOL
        : '';
    $delete_work = Base::$user->getUser()->isAdmin()
        ? '<a href=action.php?action=work_delete&id_w=' . $work['id']
            . ' title="Видалити роботу з реєстру (Зникнуть зв\'язки, автори та керівникі будуть у базі)"></a>'. PHP_EOL
        : '' . PHP_EOL;
    $files = HtmlHelper::listFiles($filesOfWork);
    $rowspan = '3';
    $row_table = <<<ROWTABLE
<tr class="{$invitateClassWork}">
            <td rowspan="{$rowspan}" class="workID"><div id="id_w{$work['id']}">{$work['id']}</div></td>
            <td colspan="4" class="title" title="Останні зміни :$date">
            <!-- Действия над работой -->
            {$title}&nbsp;&nbsp;{$link_work}&nbsp;&nbsp;{$delete_work} 
            </td>
</tr>        
<tr   class="{$invitateClassWork}">
            <td class="tdInfo">
                <strong>Секція:</strong>{$section}
                $moto $tesis
                $public
                $introduction $link_add_review
                
            </td>
            <td>$reviews</td>
            <td rowspan="1">$list_leaders</td>
            <td rowspan="1">$list_autors</td>
    </tr>
    <tr class="{$invitateClassWork}">
        <td colspan="1">$files</td>
        <td colspan="4" title="Коментарі та зауваження" >{$work['comments']}</td>
    </tr>
ROWTABLE;
    return $row_table;
}
/**
 * Строка таблици для списка рассылок
 *
 * @param int   $row_number
 * @param array $row
 */
function table_row_list_address2($row_number, array $row): string
{
    return '<tr><td>' . $row_number . '</td>'
        . '<td>' . $row['univerfull'] . '</td>'
        . '<td>' . $row['adress'] . ' ' . $row['zipcode'] . '</td>'
        . "</tr>\n";
}


/**
 * Формирует окончание предложения для письма во 2-м инф. приглашении
 *  В зависомости от количества работ изменяет окончание предложения
 *
 * @param int $col_w
 * @return string
*/
function works_declension($col_w)
{
    if (in_array($col_w, [1, 21, 31])):
        $str = 'роботу';
    elseif (in_array($col_w, [2, 3, 4, 22, 23, 24, 32, 263])):
        $str = 'роботи';
    else:
        $str = 'робіт';
    endif;

    return $col_w . '&nbsp;' . $str;
}


/**
 * @param bool $showAllInfo
 */
function listLeadersWhoArrival($showAllInfo = false){
    global $link;
    $query = "SELECT CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as  fio FROM leaders WHERE arrival='1' ORDER BY fio ASC" ;
    $text = "";
    $rowTable = "%s<br>";
    if ($showAllInfo){
        $query = "SELECT CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as fio, degreefull, statusfull, position,univerrod FROM leaders
                  LEFT JOIN positions ON positions.id = leaders.id_pos 
                  LEFT JOIN univers ON univers.id = leaders.id_u
                  LEFT JOIN degrees ON degrees.id = leaders.id_deg
                  LEFT JOIN statuses ON statuses.id = leaders.id_sat
                  WHERE arrival='1' ORDER BY fio ASC";
        $text= '(Розгорнутий)';
        $rowTable = "%s, %s, %s, %s %s<br>";

    }
    $result = mysqli_query($link, $query)
    or die('Помилка запиту функція listLeadersWhoArrival: ' . mysqli_error($link));
    echo "<h3> </h3>";
    echo "<details><summary>Список супроводжуючих/керівників, що прибули для участі у роботі журі {$text}</summary>";
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        //print_r($row);
        echo vsprintf($rowTable,$row);
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
 */
function short_list_leader_or_autors_str($id_w, $who, $showId = false)
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
 * Функция выводит только первых $num букв названия файла если длинна имени файла больше
 *
 * @param string $str
 * @param int    $num
 * @return string
 */
function file_name_format(string $str, int $num):string
{
    return (mb_strlen($str) > $num)
        ? mb_substr($str, 0, $num) . '...'
        : $str;
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
 * Print gerb
 * @param  bool $empty
 * */
 function PrintGerb($empty = true)
 {
     $str = "<!-- БЛАНК УНИВЕРСИТЕТА -->";
     $GERB = '<img class= "hGERB" src ="./../img/gerb.png" alt="herb">';
     $MON = '<div class = "hMON">МІНІСТЕРСТВО ОСВІТИ І НАУКИ УКРАЇНИ</div>';
     $DDTUfull = '<div class = "hDDTUfull">ДНІПРОВСЬКИЙ ДЕРЖАВНИЙ ТЕХНІЧНИЙ УНІВЕРСИТЕТ</div>';
     $DDTUshort = '<div class = "hDDTUshort">(ДДТУ)</div>';
     $ADRESS = '<div class = "hADRESS">вул. Дніпробудівська, 2 м. Кам’янське, 51918, тел./факс (0569) 538523</div>';
     $MAIL = '<div class = "hMAIL">Е-mail: <span>science@dstu.dp.ua</span></div>';
     $DATA = $empty ? '<div class = "hDATA">______________№____________________' : '<div class = "hDATA"><span>&nbsp;&nbsp;XX/XX/2018&nbsp;&nbsp;</span>№<span>' . TAB_SP . "108-08/10-69" . TAB_SP . "</span>";
     $DATA .= TAB_SP . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;На&nbsp;№__________________від____________</div>';

     $str .= $GERB . $MON . $DDTUfull . $DDTUshort . $ADRESS . $MAIL . $DATA;
     printf($str);

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
            if(is_file($file)){
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