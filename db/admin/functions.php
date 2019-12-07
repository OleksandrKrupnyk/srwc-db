<?php

/**
 * @author studyjquery
 * @copyright 2014
 */

use zukr\author\AuthorHelper;
use zukr\base\html\HtmlHelper;
use zukr\file\FileHelper;
use zukr\leader\LeaderHelper;
use zukr\user\UserHelper;
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
    $query = "SELECT * FROM `settings`";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Помилка зчитування налаштувань програми: " . mysqli_error($link));
    while ($row = mysqli_fetch_array($result)) {
        $parametr = $row['parametr'];
        if ("1" == $row['value'])
            $settings[$parametr] = "1";
    }
}

/**
 * Вывод странички настроек программы
 *
 */
function print_page_settings()
{
    global $link;
    global $settings;
    $query = 'SELECT * FROM `settings`';
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Помилка зчитування налаштувань програми: " . mysqli_error($link));
    echo "<form action=\"settings.php\" method=POST>\n<table>\n";
    echo "<h2>Коротка інформація про систему</h2>";
    echo "<h5>Обмеження розміру файлу на завантаження:". ini_get('upload_max_filesize')."</h5>";
    echo "<h5>Кодування за замовчуванням:". ini_get('default_charset')."</h5>";
    echo "<h5>Шляхи до підключення розширень dll/lib:". ini_get('extension_dir')."</h5>";
    echo "<h5>Шляхи до файлів включення файлів php:". ini_get('include_path')."</h5>";
    echo "<h5>Максимальний розмір POST запиту:". ini_get('post_max_size')."</h5>";
    echo "<tr><th>Налаштування</th><th>Значення</th></tr>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr><td>" . $row['description'] . "</td><td>";
        chk_box($row['parametr'], "", $row['value']);
        echo "</td></tr>";
    }
    echo "</table>\n<input type=\"submit\" name=\"SAVE_SETTINGS\" value=\"Записати зміни\">\n</form>";
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
    $tz_id = (int)$_SESSION['id'] == 0 ? $tz_id : $_SESSION['id'];
    $query = "INSERT INTO `log` (`tz_id`,`date`,`action`,`table`,`action_id`,`tz_ip`)\n
        VALUES\n
        ('{$tz_id}',NOW(),'{$action}','{$table}','{$action_id}','{$_SERVER['REMOTE_ADDR']}')";
    //echo( $query);
    $result = mysqli_query($link, $query)
    or die("Помилка запису журналу: " . mysqli_error($link) . $action_id);
}

/** * ********************************************************************************
 *
 *  Список университетов всех которые есть
 *  Возвращает значение в переменной id_u (тип integer)
 * @param int $chk  номер вуза
 * @param int $size Размер списка
 * @param bool $invite
 * @param bool $shortname
 * @param bool $checkin
 *  list_univers(<номер отмеченого вуза>,<размер списка>,<1-отображать только из 1ИнфСооб>)
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function list_univers($chk, $size, $invite = true,$shortname = false, $checkin=false)
{
    global $link;
    $query = "SELECT `univers`.* "."FROM univers ";
    $query .= ($checkin ==1)?" RIGHT JOIN works ON works.id_u=univers.id ":"";
    $query .= ($invite == 1)?" WHERE `invite`= 1  OR `univers`.`id` = 1 " : ""; //`id` = 1 - это ВУЗ ДДТУ
    $query .= ($checkin ==1)?" GROUP BY univers.id ":"";
    $query .= "ORDER BY univer";
    //echo $query;
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query: " . mysqli_error($link));
    $idString = ($shortname == 0)? "selunivers":"shortlistunivers";

    echo "<select id=\"{$idString}\" name=\"id_u\" size=\"{$size}\" required><option value=\"-1\" disabled selected>Університет...</option>\n";
    while ($row = mysqli_fetch_array($result)) {
        $NameUniver  = ($shortname == 0)? "{$row['univer']}({$row['univerfull'] })" : "{$row['univer']}";
        if (isset($chk) && $chk != "" && $row['id'] == $chk) {
            echo "<option value=\"{$row['id']}\" selected>{$NameUniver}</option>\n";
        } else {
            if (isset($_COOKIE['cid_u']) && $row['id'] == $_COOKIE['cid_u'] && $chk == "") {
                echo "<option value=\"{$row['id']}\" selected>{$NameUniver}</option>\n";
            } else
                echo "<option value=\"{$row['id'] }\">{$NameUniver}</option>\n";
        }
    }
    echo "</select>\n";
}


/**
 * Список для выбора рецензентов/Руководителей для внесения данных в таблицу рецензий
 * @param Integer $id Номер рецензии которая редактируется (-1 - если создается новая)
 * @param Integer $id_u Шифр университета из которого работа, чтобы исключить рецензентов не из этого ВУЗа (-1)
 * @param Integer $id_w Шифр работы, что бы исключить рецензии две рецензии от одного рецензента
 */
function cbo_reviewers_list($id = -1,$id_u,$id_w){
    global $link;
    if($id == -1) {
        $query = "SELECT leaders.id, leaders.suname, leaders.name, leaders. lname, positions.position, degrees.degree, statuses.status, univers.univer FROM leaders \n" .
            "JOIN positions ON leaders.id_pos = positions.id \n" .
            "JOIN degrees ON leaders.id_deg = degrees.id \n" .
            "JOIN statuses ON leaders.id_sat = statuses.id \n" .
            "JOIN univers ON leaders.id_u=univers.id \n" .
            "WHERE (leaders.review=TRUE AND leaders.id_u <> {$id_u}) \n" .
            "AND (leaders.id <> (SELECT reviews.review1 FROM reviews WHERE reviews.id_w={$id_w}) \n" .
            "OR (SELECT reviews.review1 FROM reviews WHERE reviews.id_w={$id_w}) IS NULL) \n" .
            "ORDER BY suname ASC";
    }else{
        $query = "SELECT leaders.id, leaders.suname, leaders.name, leaders. lname, positions.position, degrees.degree, statuses.status, univers.univer FROM leaders \n" .
            "JOIN positions ON leaders.id_pos = positions.id \n" .
            "JOIN degrees ON leaders.id_deg = degrees.id \n" .
            "JOIN statuses ON leaders.id_sat = statuses.id \n" .
            "JOIN univers ON leaders.id_u=univers.id \n" .
            "WHERE leaders.review=TRUE \n ".
            "ORDER BY suname ASC";
    }
        //echo "<pre>{$query}</pre>";
        mysqli_query($link, "SET NAMES 'utf8'");
        mysqli_query($link, "SET CHARACTER SET 'utf8'");
        $result = mysqli_query($link, $query)
        or die("Invalid query in function cbo_reviewers_list : " . mysqli_error($link));
        echo "<select size=\"1\" id=\"reviewer\" name=\"reviewer\"  required>\n";
        if (mysqli_num_rows($result) > 0) {//выводить если есть хотябы одна строка

            while ($row = mysqli_fetch_array($result)) {
                $selected = ($row['id'] == $id) ? "selected" : "";
                echo "<option value=\"{$row['id']}\" $selected>{$row['suname']} {$row['name']} {$row['lname']}, {$row['univer']}, {$row['position']}, {$row['degree']}</option>\n";
                //print_r($row);
            }

        } else {
            echo "<option value=\"-1\" disabled selected>Додайте ще одного рецензента з іншого ВНЗ</option>\n";
            echo TAB_SP . TAB_SP . "<p></p>";
        }
        echo "</select>\n";
}

/**
 * @param int $id_w ID of work in table works
 * @param bool $href TRUE if you need to show link for edit
 * @return string Numeric list of reviews with links
 */
function list_reviews_for_one_work($id_w,$href = false,$loginId="0")
{
    global $link;
    $query = "SELECT `reviews`.`id`, `reviews`.`id_w`, leaders.id_tzmember, reviews.conclusion,  
              CONCAT(`leaders`.`suname`,' ',`leaders`.`name`,' ',`leaders`.`lname`) AS fio,
              (`actual`+`original`+`methods`+`theoretical`+`practical`+`literature`+`selfcontained`+`design`+`publication`+`government`+`tendentious`) AS sumball 
              FROM `reviews` 
              JOIN `leaders` ON `leaders`.`id` = `reviews`.`review1` 
              WHERE id_w = {$id_w}";

    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query in function list_reviews_for_one_work : " . mysqli_error($link));
    $allsum = 0;
    $allconclusion = "";
    $str = "<ol>";
    while ($row = mysqli_fetch_array($result)) {
        $allsum +=$row['sumball'];
        $str .= "<li>\n";
        if(true == $href) {
            $str .= "<a href=\"action.php?action=review_edit&id={$row['id']}\" title=\"Ред. Рец.:{$row['fio']}\">&#9998;:[{$row['sumball']}]</a>\n";
            if ($loginId == $row['id_tzmember'] OR ($loginId=="1") OR ($loginId=="2")) {
                $str .="<a href=\"action.php?action=review_delete&id={$row['id']}&id_w={$row['id_w']}\"  title=\"Видалити рецензію\"></a>\n";
            }
        }
            else {
                $str .= "<a href=\"index.php?action=review_view&id={$row['id']}\" title=\"[{$row['sumball']}]\">Реценз.</a>\n";
            }
            if($row['conclusion']==1){$allconclusion .= "ТАК&nbsp;";}
            else {$allconclusion .= "НІ&nbsp;";}

        $str .= "</li>";
    }

    $str .= "</ol>";

    $str .= "<p>&nbsp;&nbsp;&nbsp;<strong>&sum;:{$allsum}</strong>&nbsp;{$allconclusion}</p>";
    return $str;
}




/** Выводит список всех работ для задания рецензии
 * @param int $id_w ID in table works wihch must be selected
 */
function list_works($id_w=-1){
    global $link;
    $query = "SELECT works.id, works.title, works.id_u FROM works ORDER BY  works.title ASC";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query in function list_works : " . mysqli_error($link));
    echo "<select size=\"1\" name=\"id_w\" required>\n";
    while ($row = mysqli_fetch_array($result)) {
        $selected = ($id_w == $row['id'])? "selected":"";
        if(count_review($row['id'])<2) {
            $row['title'] = left($row['title'],70)."...";
            echo "<option title=\"{$row['id_u']}\" value=\"{$row['id']}\"  $selected >{$row['title']}</option>\n";

        }
    }
    echo "</select>\n";

}

/** * ********************************************************************************
 *
 * Список из таблицы, по полю, выделить да/нет, размер списка=1,Подсказка
 * <select></select>
 * @param string $table
 * @param string $pole Sorting by pole
 * @param $chk
 * @param int $size Size of selecting list
 * @param string $caption
 * @param string $name Name of html property such "name"
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function list_($table, $pole, $chk, $size = 1, $caption = "Обрати...", $name = "")
{
    global $link;
    //Формируем запрос на поле указанное как пареметр функции
    //$query = "SELECT * FROM `" . $table . "` ORDER BY  `" . $table . "`.`" . $pole . "` ASC";
    $query = "SELECT * FROM `{$table}` ORDER BY  `{$table }`.`{$pole}` ASC";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query in function list_ : " . mysqli_error($link));
    if ("" == $name) {
        echo "<select size=\"{$size}\" name=\"{$pole}\" required>\n";
    } else {
        echo "<select size=\"{$size}\" name=\"{$name}\" required>\n";
    }
    echo "<option value =\"-1\" disabled>" . $caption . "</option>\n";
    while ($row = mysqli_fetch_array($result)) {
        if ((isset($_COOKIE['c' . $pole]) && $row['id'] == $_COOKIE['c' . $pole]) || (isset($chk) && $chk == $row['id']))
            echo "<option value=\"{$row['id']}\" selected >{$row[$pole]}</option>\n";
        else
            echo "<option value=\"{$row['id']}\" >{$row[$pole]}</option>\n";
    }
    echo "</select>\n";
}

/** * ********************************************************************************
 *
 * Список работ по конкретному вузу
 * <select></select>
 * @param int $id_u
 * @param string $pole
 * @param int $select
 * @param int $size Size of field
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function list_works_of_univer($id_u, $pole, $select, $size)
{
    global $link;
    //Формируем запрос на поле указанное как пареметр функции
    $query = "SELECT * FROM `works` WHERE `id_u`='{$id_u}'";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query in function list_works_of_univer : " . mysqli_error($link));
    echo "<select size=\"" . $size . "\" id=\"selwork\" name=\"id_w\">\n";
    echo "<option value =\"-1\" disabled selected>Робота...</option>\n";
    while ($row = mysqli_fetch_array($result)) {
        $str = "<option value=\"{$row['id']}\"";
        $str .= ($select == $row['id']) ? " selected " : "";
        $str .= ">{$row[$pole]}</option>\n";
        echo $str;
    }
    echo "</select>\n";
}

/** * ********************************************************************************
 *
 * Список [Фамилия Имя Отчество]
 *  <select></select>
 * @param string $table  Таблица
 * @param string $pole    Имя параметра name
 * @param int $id_u id of Univercity
 * @param int $size Size of list
 * @param bool $selecttag true or false
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function list_fio($table, $pole, $id_u, $size, $selecttag = true)
{
    global $link;
    if ($id_u == "") {
        $query = "SELECT * FROM `{$table}` ORDER BY  `suname` ASC";
    } else {
        $query = "SELECT * FROM `{$table}` WHERE `id_u`='{$id_u}' ORDER BY  `suname` ASC";
    }
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query: " . mysqli_error($link));
    //Проверка а вообще есть результаты по запросу
    $count = mysqli_num_rows($result);
    if ($count == 0) {
        echo "Незнайдено";
    }
    if (true == $selecttag) {
        echo "<select size=\"$size\" name=\"$pole\"><option value=\"-1\" selected disabled>Оберіть...</option>\n";
    }

    while ($row = mysqli_fetch_array($result)) {
        echo "<option value=\"{$row['id']}\" >" . $row['suname'] . " " . $row['name'] . " " .
            $row['lname'] . "</option>\n";
    }

    if (true == $selecttag) {
        echo "</select>\n";
    }
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
    $query = "SELECT `leaders`.*,`positions`.`position`  FROM `leaders`\n"
        . "JOIN `positions` ON `leaders`.`id_pos` = `positions`.`id`\n"
        . "WHERE `leaders`.`id_u`='" . $id_u . "' \n";
    $query .= ($check) ? "" : " AND `leaders`.`invitation` = TRUE \n";
    $query .= "ORDER BY  `suname` ASC";

    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query: " . mysqli_error($link));
    $count = mysqli_num_rows($result);
    if ($count == 0) {
        return false;
    }
    echo "\t\t<ol>\n";
    while ($row = mysqli_fetch_array($result)) {
        echo "\t\t\t<li>" . $row['suname'] . " " . $row['name'] . " " . $row['lname'];
        if (true == $check) {
            echo "<input type=\"hidden\" value=\"" . $row['id'] . "\">";
            chk_box("invitation", "Запросити", $row['invitation']);
        } else {
            echo ", " . $row['position'];
        }
        echo "</li>\n";
    }
    echo "\t\t</ol>\n";
}


/**
 * Данные все данные по 1 полю в таблице
 *
 * @param string $table
 * @param string $field
 * @param string $value
 * @param bool   $u Bool
 * @return array
 */
function fullinfo($table, $field, $value)
{
    global $link;
    $query = "SELECT * FROM `{$table}` WHERE `{$field }`='{$value}'";
    $result = mysqli_query($link, $query)
    or die("Помилка запиту функція fullinfo: " . mysqli_error($link));
    return mysqli_fetch_array($result);
}

/**
 * список файлов работы
 *
 * @param int $id_w
 * @param string $typeoffile
 * @return String
 */
function list_files($id_w, $typeoffile = 'all')
{
    global $link;
    if ($id_w != '') {
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


        mysqli_query($link, "SET NAMES 'utf8'");
        mysqli_query($link, "SET CHARACTER SET 'utf8'");
        $result = mysqli_query($link, $query)
        or die("Помилка запиту функція list_files: " . mysqli_error($link));
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
            $str = "";
    } else {
        $str = "<mark>Нема файлів для відображення</mark>";
    }
    return $str;
}

/**
 * Выдает количество авторов (руководителей) у работы
 *
 * @param string $table Table in MySQL
 * @param int    $id_w  id Work
 * @return Integer
 */
function count_la($table, $id_w)
{
    global $link;
    $query = "SELECT Count(id_w) FROM `" . $table . "`\n"
        . "WHERE `id_w`='" . $id_w . "'";
    $result = mysqli_query($link, $query)
    or die('Помилка запиту функція count_la: ' . mysqli_error($link));
    $row = mysqli_fetch_array($result);
    return $row[0];
}

/**
 * @param int $id_w
 * @return int mixed
 *
 */
function count_review($id_w){
    global $link;
    $query = "SELECT COUNT(id_w) FROM `reviews` WHERE `id_w` = {$id_w}";
    $result = mysqli_query($link, $query)
    or die("Помилка запиту функція count_review: " . mysqli_error($link));
    $row = mysqli_fetch_array($result);
    return $row[0];
}

/**
* @param string $table
*/
function list_emails($table){
    global $link;
    $query = '';
    $get_text = '';
    //Формируем запрос на получение таблицы
    if($table === 'autors'){//авторы работ
        $query= "SELECT works.title,autors.id, autors.suname, autors.name,autors.lname,autors.hash,autors.email,autors.email_recive,autors.email_date
                 FROM works
                 LEFT JOIN wa ON wa.id_w = works.id
                 LEFT JOIN autors ON wa.id_a = autors.id
                 WHERE works.invitation = '1'
                 ORDER BY autors.suname";
        $get_text = "&t=a";
    }
    elseif ($table == "leaders"){
        $query= "SELECT works.title,leaders.id, leaders.suname, leaders.name,leaders.lname,leaders.hash,leaders.email,leaders.email_recive,leaders.email_date
                 FROM works
                 LEFT JOIN wl ON wl.id_w = works.id
                 LEFT JOIN leaders ON wl.id_l = leaders.id
                 WHERE works.invitation = '1'
                 ORDER BY leaders.suname ";
        $get_text = "&t=l";
    }

    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    //echo $query."\n";
    $result = mysqli_query($link, $query)
    or die("Invalid query функція list_emails: " . mysqli_error($link));
    $sub_row_str_nomail ="<details>\n<summary>Не надали адресу</summary>\n<ol>";
    $sub_row_str = "<details>\n<summary>Список отримувачів</summary>\n<ol name=" . $table . ">";
     while ($row = mysqli_fetch_array($result)) {


         if($row['email'] != "") {
             $sub_row_str .= "<li>".$row['suname']." ".$row['name']." ".$row['lname'];
             $sub_row_str .= "<a href=\"getmails.php?hash=" . $row['hash'] . $get_text . "\">Получить письмо!</a>";
             if ($row['email_recive'] == 1){$sub_row_str .= " [The email have recived and read.".$row['email_date']." ] ";}
             $sub_row_str .="<input type=\"hidden\" name=emails[] value =".$row['email']." >";
             $sub_row_str .="<input type=\"hidden\" name=whom[]   value =\"".$row['suname']." ".$row['name']." ".$row['lname']."\" >";
             $sub_row_str .="<input type=\"hidden\" name=hashs[]  value =\"".$row['hash']."\">";
             $sub_row_str .="<input type=\"hidden\" name=titles[] value =\"".$row['title']."\">";
             $sub_row_str .= "</li>\n";
         }
         else
            {$sub_row_str_nomail.= "<li>".$row['suname']." ".$row['name']." ".$row['lname']." <mark>Поштова скринька відсутня!</mark>";}

        //print_r($row);

        }
    $sub_row_str_nomail .="</ol>\n</details>";
    $sub_row_str .= "</ol>\n</details>\n".$sub_row_str_nomail;
    echo $sub_row_str;
}


/** * ********************************************************************************
 * Список авторов или руководителей
 * @param string $table
 * @param bool $phone
 * @param bool $email
 * @param bool $hash
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function list_autors_or_leaders($table, $phone = false, $email = false,  $hash=false,$onlyReviwers = false)
{
    global $link;
    global $FROM;
    $table = ($table == "") ? "autors" : $table;
    $id = ($table === "autors") ? "id_a" : "id_l";
	//."JOIN `positions` ON `leaders`.`id_pos` = `positions`.`id`\n
    if ($table === "autors") {$query = "SELECT autors.*, univers.univer FROM `autors` JOIN univers ON autors.id_u = univers.id ORDER BY `suname` ASC";}
    else{
        $query = "SELECT leaders.*, positions.position,degrees.degree,statuses.status,univers.univer FROM leaders 
JOIN positions ON leaders.id_pos = positions.id
JOIN degrees ON leaders.id_deg = degrees.id
JOIN statuses ON leaders.id_sat = statuses.id
JOIN univers ON leaders.id_u = univers.id";
        $query .= ($onlyReviwers == true)? " WHERE leaders.review = '1' ":"";
        $query .= " ORDER BY `suname` ASC";
    }


    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query функція list_autors_or_leaders: " . mysqli_error($link));
    $sub_row_str = "<ol name=" . $table . ">";
    while ($row = mysqli_fetch_array($result)) {
        //print_r($row);
        $sub_row_str .= "<li data-index=" . $row['id'] . " id=" . $row['id'] . " title=\"Останні зміни :" . htmlspecialchars($row['date']) . "\" >";
        $sub_row_str .= "<a href=action.php?action=" . rtrim($table, "s") . "_edit&" . $id . "=" . $row['id'] . "&FROM={$FROM}  title=\"Ред.{$row['univer']}\">";
        $sub_row_str .= $row['suname'] . " " . $row['name'] . " " . $row['lname'];
        $sub_row_str .= "</a>  ";
        if($onlyReviwers == false) {
            $sub_row_str .= "<a href=#remove title=\"Видалити\n з реестру\"></a>";
        }
        $sub_row_str .= ($row['arrival'] == 1) ? "<span title=\"Прибув на конференцію\">&nbsp;[&radic;]&nbsp;</span>" : "";

       $get_text = "&t=a"; // для GET запроса в хеш сторку
	if($table == "leaders"){
		$sub_row_str .= " ".$row['position'];
		$sub_row_str .= " ".$row['degree'];
		$sub_row_str .= " ".$row['status'];
        $get_text = "&t=l"; // для GET запроса в хеш сторку
	}

	if($onlyReviwers == false) {
        $phone_number = (($row['phone'] <> "") && ($phone == true)) ? $row['phone'] : "відсутній";
        $email_text = (($row['email'] <> "") && ($email == true)) ? "<strong>e-mail:</strong><a href=\"mailto:" . $row['email'] . "\">" . $row['email'] . "</a>" : "";
        $sub_row_str .= ($phone == true) ? "<span id=\"phone\">" . $phone_number . "</span>" : "";
        $sub_row_str .= "" . $email_text;


        $sub_row_str .= ($hash==true)? "<a href=\"getmails.php?hash=".$row['hash'].$get_text."\">Получить письмо!</a>":"";
        if (($row['email_recive'] == 1) && ($hash==true)){$sub_row_str .= " [The email have recived and read.".$row['email_date']." ] ";}
        $sub_row_str .= "<a href=\"lists.php?list=badge_{$table}&badge={$row['id']}\" title=\"Друкувати посвідчення\"></a>";
        $sub_row_str .= "<input type=\"checkbox\" name=\"works_id[]\" value=\"{$row['id']}\">";
        $sub_row_str .= "</li>\n";
    }
    }
    $sub_row_str .= "</ol>\n";
    echo $sub_row_str;
}

/**
 * Cписок авторов или руководителей по ВУЗУ
 * * @param string $table
 */
function list_persons($table)
{
    global $link;

    $query = "SELECT * FROM `{$table}` ORDER BY suname ASC ";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die("Invalid query функція list_persons: " . mysqli_error($link));
    while ($row = mysqli_fetch_array($result)) {
        echo $row['name'];
        echo '<br>';
    }
}

/** * ********************************************************************************
 *
 * Список курса обучения Курс обучения выделяет выбраный
 * <select></selct>
 * @param int $id
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function cbo_curse($id)
{
    $str = "<select size=\"1\" name=\"curse\" title=\"Курс навчання\">\n";
    $str .= "<option disabled selected>Курс...</option>\n";
    for ($i = 1; $i < 7; $i++) {
        $str .= "<option value=" . $i;
        $str .= ($id == $i) ? " selected " : "";
        $str .= ">" . $i . "</option>\n";
    }

    $str .= "</select>\n";
    echo $str;
}

/** * ********************************************************************************
 *
 * Список выбора места работы
 * @param String $id
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function cbo_place($id)
{
    $str = "<select size=\"1\" name=\"place\" title=\"Призове місце:(D-Диплом за участь)\">\n";
    $str .= "<option disabled selected>Місце...</option>\n";
    $placeArray = ["D", "I", "II", "III"];
    foreach ($placeArray as $i => $placeOption) {
        $selected = ($id == $placeOption) ? " selected " : "";
        $str .= "<option value='{$placeOption}' {$selected} >{$placeOption}</option>\n";
    }
    $str .= "</select>\n";
    echo $str;
}

/** * ********************************************************************************
 * Выводит чекбох и делает отметку если = 1
 * @param string $name
 * @param string $title
 * @param string $value
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function chk_box($name, $title, $value)
{
    $str = "<input type='checkbox' name='{$name}' title='{$title}' ";
    if ($value != "")
        $str .= ($value == 1) ? 'checked' : '';

    $str .= ' >';
    echo $str;
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
    $row_univer = '
<tr><td colspan="5" class="univerTitle">
    <div id=id_u' . $id_u . ' style="display:inline;margin-right:5px">' . $univer . '</div><a href="action.php?action=univer_edit&id_u=' . $id_u
        . '&FROM=' . $FROM . '" title="Редагувати данні університету">';
    $row_univer .= $univer_title . '</a></td></tr>';
    return $row_univer;
}

/**
 * Выводит рядок в таблице просмотра данных о работе
 *
 * @param array  $work
 * @param string $loginId
 **/
function print_work_row($work, $loginId = '0')
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

    $uh = UserHelper::getInstance();
    $userAdmins = $uh->getIsAdmin();
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
    $link_add_review = (count_review($work['id']) < 2)
        ? '<a href="action.php?action=review_add&id_w=' . $work['id'] . '&id_u=' . $work['id_u'] . '">додати рецензію</a>'
        : '';

    $reviews = list_reviews_for_one_work($work['id'], true, $loginId);

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
    $delete_work = in_array($loginId,$userAdmins)
        ? '<a href=action.php?action=work_delete&id_w=' . $work['id']
            . ' title="Видалити роботу з реєстру (Зникнуть зв\'язки, автори та керівникі будуть у базі)"></a>'. PHP_EOL
        : '<a href="#" title="Вам заборонено видаляти роботу"></a>' . PHP_EOL;
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

/** * ********************************************************************************
 * Строка таблици для отображения в таблице приглашений
 * $href если true то вывести ссылку на редактирование работы
 * @param array $row рядок запроса
 * @param bool $href
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function print_row_table_section_select($row, $href = false)
{
    $id_title = ($href) ? "<a href=action.php?action=work_edit&id_w=" . $row['id'] . " title=\"Редагувати роботу\">" : "";
    $id_title .= $row['id'];
    $id_title .= ($href) ? "</a>\n" : "";
    echo "<tr><td>{$id_title}</td><td>{$row['title']} ({$row['univer']})<strong>[{$row['balls']}]</strong><{$row['countReview']}></td>";
    echo '<td>';
    chk_box('invitation', 'Відмітити для запрошення', $row['invitation']);
    echo "</td>";
    echo "<td>";
    list_("sections", "section", $row['id_sec'], 1, "Секція...");
    echo '</td></tr>';
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
 * Блок адресса на конверте
 *
 * @param array $row
 */
function print_adress2(array $row): string
{
    return "<strong><ins>" . $row['univerfull'] . "</ins></strong><br>\n<em>" . $row['adress'] . "</em><br>\n<strong>" . $row['zipcode'] . '</strong>';
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
 * Список всех имен в базе включая авторов и руководителей
 * @param $pole_id
 */
function print_datalist_name($pole_id)
{
    global $link;

    $query = ($pole_id == "name") ?
        "SELECT `autors`.`name` FROM `autors` Group by  `autors`.`name`  
              UNION 
              SELECT `leaders`.`name` FROM `leaders`  Group by  `leaders`.`name` 
              ORDER BY `name`" :
        "SELECT `autors`.`lname` FROM `autors` Group by  `autors`.`lname`  
              UNION 
              SELECT `leaders`.`lname` FROM `leaders`  Group by  `leaders`.`lname` 
              ORDER BY `lname`";

    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die('Помилка запиту функція print_datalist_name: ' . mysqli_error($link));
    echo "<datalist id=\"" . $pole_id . "\">\n";
    while ($row = mysqli_fetch_array($result)) {
        echo "<option>" . $row[$pole_id] . "</option>\n";
    }
    echo "</datalist>\n";
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
        $text="(Розгорнутий)";
        $rowTable = "%s, %s, %s, %s %s<br>";

    }
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
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
    $query = ($who == 'wa') ?
        "SELECT autors.id, 
        CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as fio\n 
        FROM `wa` left outer join `autors` ON `wa`.`id_a`=`autors`.`id`\n" :
        "SELECT leaders.id, CONCAT(`suname`,'&nbsp;',left(`name`,1),'.',left(`lname`,1),'.') as  fio,
        `position`,`status`,`degree`FROM `wl` 
        left outer join `leaders` ON `wl`.`id_l`=`leaders`.`id`
        left outer join `positions` ON `leaders`.`id_pos` = `positions`.`id`
        left outer join `statuses` ON `leaders`.`id_sat` = `statuses`.`id`
        left outer join `degrees` ON `leaders`.`id_deg` = `degrees`.`id`";


    $query .= "WHERE `id_w`='" . $id_w . "' ORDER BY `fio` ASC";
    //echo $query;
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");

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
 *  Список университетов которые приглашены на конкурс
 *
 * @param int $size
 */
function list_univers_reseption($size)
{
    global $link;
    $query = "SELECT `univers`.`id`,`univers`.`univer`,`univers`.`univerfull` FROM `univers` RIGHT OUTER JOIN `works` ON  `works`.`id_u` = `univers`.`id` GROUP BY univer ORDER BY univer";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die('Invalid query функція list_univers_reseption: ' . mysqli_error($link));
    echo "<select id=\"univer_reseption\" name=\"id_u\" size=\"$size\"><option value=\"-1\" disabled selected>Університет...</option>\n";
    while ($row = mysqli_fetch_array($result)) {
        echo "<option value=" . $row['id'] . ">" . $row['univer'] . " (" . $row['univerfull'] . ")</option>\n";
    }
    echo "</select>\n";
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
 *  Функция возвращает строку с левой стороны от строки
 *
 * @param string $str
 * @param int    $num
 * @return string
 */
function left($str, $num)
{
    return mb_substr($str, 0, $num);
}

/**
 *  Функция возвращает строку с правой стороны от строки
 *
 * @param string $str
 * @param int    $num
 * @return string
 */
function right($str, $num)
{
    $len = strlen($str);
    return mb_substr($str, $len - $num, $len);
}

/**
 * Функция выводит конструкцию <select></select> для выбора аудитории для списка оценивания
 *
 * @param string $room
 * @param array  $rooms
 * @return string
 */
function select_room($room, $rooms = ['7-43', '7-53', '7-54'])
{
    $list = '';
    foreach ($rooms as $item) {
        $selected = $room === $item ? 'selected' : '';
        $list     .= "<option value='{$item}' $selected >$item</option>" . PHP_EOL;
    }
    return "<select size='1' name='room' title='Аудиторія'>\n{$list}</select>\n";
}

/**
 * Функция выводит конструкцию <select></select> для выбора должности руководителя ВУЗ
 * @param String $posada
 */
function select_positionVNZ($posada)
{
    $str = "<select size='1' name='posada' title='Посада керівника ВНЗ'>\n";
    $positions = [
        'Ректору',
        'В.о.ректора',
        'Директору',
        'Начальнику інституту',
        'Начальнику академії',
        'Начальнику військового інституту'
    ];
    foreach ($positions as $position) {
        $str .= "<option value='{$position}'";
        $str .= ($posada == $position) ? ' selected ' : '';
        $str .= ">{$position}</option>\n";
    }
    $str .= "</select>\n";
    echo $str;
}

/**
 * Возращает строку с надписью в дипломе (I - першого ступеня)
 * @param  string $place
 * @return string $str
 */
function diplom_place($place)
{
    $str = 'ПУСТИЙ РЯДОК';
    switch ($place) {
        case 'I': {
            $str = 'ПЕРШОГО СТУПЕНЯ';
        }
            break;
        case 'II': {
            $str = 'ДРУГОГО СТУПЕНЯ';
        }
            break;
        case 'III': {
            $str = 'ТРЕТЬОГО СТУПЕНЯ';
        }
            break;
    }
    return $str;
}

/**
 *  Возвращает строку студент (студентка в звсисимости от окончания отчества)
 *
 * @param string $O
 * @return string
 */
function student_ka($O)
{
    return right($O, 1) === 'ч'
        ? 'студент'
        : 'студентка';
}

/**
 * @param string $str
 * @return string
 */
function AnalizeMysqlError($str){
    $result = "";
    //Проверяем не пустая ли к нам пришла строка
    if ('' === $str){ $result = "Пустая строка";}
    //Разбиваем строку на строки по разделителю
    $array_str = explode(' ',$str);
    switch($array_str[0]){
        case 'Duplicate' : {$result="Ошибка! Дублирование данных :".$str."\n"; }break;
        default: {$result=$str."\n";} break;
    }
    return $result;
}

/**
 * @param string $page
 */
function Go_page($page) {
    header('location: ' . $page);
    exit();
}

/**
 * Print gerb
 * @param  bool $empty
 * */
 function PrintGerb($empty = true){
    $str = "\t\t\t<!-- БЛАНК УНИВЕРСИТЕТА -->\n";
    $GERB = "<img class= \"hGERB\" src =\"./../img/gerb.png\">\n";
    $MON = "<div class = \"hMON\">МІНІСТЕРСТВО ОСВІТИ І НАУКИ УКРАЇНИ</div>\n";
    $DDTUfull = "<div class = \"hDDTUfull\">ДНІПРОВСЬКИЙ ДЕРЖАВНИЙ ТЕХНІЧНИЙ УНІВЕРСИТЕТ</div>\n";
    $DDTUshort = "<div class = \"hDDTUshort\">(ДДТУ)</div>\n";
    $ADRESS = "<div class = \"hADRESS\">вул. Дніпробудівська, 2 м. Кам’янське, 51918, тел./факс (0569) 538523</div>\n";
    $MAIL = "<div class = \"hMAIL\">Е-mail: <span>science@dstu.dp.ua</span></div>\n";
    $DATA = (true == $empty)? "<div class = \"hDATA\">______________№____________________":"<div class = \"hDATA\"><span>&nbsp;&nbsp;XX/XX/2018&nbsp;&nbsp;</span>№<span>".TAB_SP."108-08/10-69".TAB_SP."</span>";
    $DATA .= TAB_SP."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;На&nbsp;№__________________від____________</div>\n";

    $str .= $GERB.$MON.$DDTUfull.$DDTUshort.$ADRESS.$MAIL.$DATA;
    printf($str);

 }

/**
 * @param $action_string
 */
function execute_get_action($action_string)
{
    if(is_string($action_string))
    {
         $action = explode('_', $action_string);
        if (count($action) === 2){
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
 * @param $action_string
 */
function execute_post_action($action_string)
{
    if(is_string($action_string))
    {
        $action = explode('_', $action_string);
        if (count($action) === 2){
            $directory = $action[0];
            $path[] = $directory;
            $fileName = $action[1] . '.php';
            $path[] = $fileName;
            $file = implode(DIRECTORY_SEPARATOR, $path);
            if(is_file($file)){
                include_once $file;
            }
        }
    }
}