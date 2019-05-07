<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:52
 */

$page = "action.php?action=work_link";
$_POST['id_u'] = (int)$_POST['id_u'];
$_SESSION['id_u'] = $_POST['id_u'];
/*echo '<pre>';
print_r($_POST);
echo '</pre>';*/

// Добавление в таблицу данных работы
$_POST['title'] = trim(addslashes($_POST['title']));
$_SESSION['title'] = $_POST['title'];
$_POST['motto'] = trim(addslashes($_POST['motto']));
$_POST['public'] = trim(addslashes($_POST['public']));
$_POST['introduction'] = trim(addslashes($_POST['introduction']));
$_POST['section'] = (int)$_POST['section'];
$tesis = ($_POST['tesis'] == "") ? 0 : 1;
$dead = ($_POST['dead'] == "") ? 0 : 1;
$_POST['comments'] = trim(addslashes($_POST['comments']));
$query = "INSERT INTO `works` (`id_u`,`title`,`motto`,`id_sec`,`public`,`introduction`,`tesis`,`dead`,`date`,`comments`)
                    VALUES ('{$_POST['id_u']}','{$_POST['title']}','{$_POST['motto']}','{$_POST['section']}','{$_POST['public']}','{$_POST['introduction']}','{$tesis}','{$dead}',NOW(),'{$_POST['comments']}')";
//echo $query;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query);
// or die("Полка запису дія work_add: " . mysqli_error($link));
if (mysqli_error($link)==''){
    $id_w = mysqli_insert_id($link);
    log_action($_POST['action'], "works", $id_w);
}else{ // Выполнять если есть ошыбка
    $error_message .= AnalizeMysqlError(mysqli_error($link));
}



// Добавление в таблицу данных автора
//Добавить проверку почты
if( 888 == $_POST['id_a']){
    $_POST['sunameA'] = trim(addslashes($_POST['sunameA']));
    $_POST['nameA'] = trim(addslashes($_POST['nameA']));
    $_POST['lnameA'] = trim(addslashes($_POST['lnameA']));
    $_POST['curse'] = (int)$_POST['curse'];
    $_POST['emailA'] = trim(addslashes($_POST['emailA']));
    if (isset($_POST['phoneA'])) {
        $_POST['phoneA'] = trim($_POST['phoneA']);
    } else $_POST['phoneA'] = '';
    $hash = md5($_POST['sunameA'].$_POST['nameA'].$_POST['lnameA']);
    $query = "INSERT INTO `autors` (`id_u`,`suname`,`name`,`lname`, `curse`,`email`,`active`,`arrival`,`phone`,`date`,`hash`)
            		VALUES ('{$_POST['id_u']}','{$_POST['sunameA']}','{$_POST['nameA']}','{$_POST['lnameA']}','{$_POST['curse']}','{$_POST['emailA']}','0','0','{$_POST['phoneA']}',NOW(),'{$hash}')";
    //echo $query;
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query);
    //    or die("Полка запису дія autor_add: " . mysqli_error($link));
    if (mysqli_error($link)==''){
        $id_a = mysqli_insert_id($link);
        log_action($_POST['action'], "autors", $id_a);
    }else{ // Выполнять если есть ошыбка
        $error_message .= AnalizeMysqlError(mysqli_error($link));
    }
}else{
    $id_a = $_POST['id_a'];
}



//Добавление в таблицу данных преподвателя
if( 888 == $_POST['id_l']){
    $_POST['sunameL'] = trim(addslashes($_POST['sunameL']));
    $_POST['nameL'] = trim(addslashes($_POST['nameL']));
    $_POST['lnameL'] = trim(addslashes($_POST['lnameL']));
    $_POST['position'];
    $_POST['statusfull'];
    $_POST['degree'];
    $_POST['emailL'] = trim(addslashes($_POST['emailL']));
    if (isset($_POST['phoneL'])) {
        $_POST['phoneL'] = trim($_POST['phoneL']);
    } else $_POST['phoneL'] = '';
    $hash = md5($_POST['sunameL'].$_POST['nameL'].$_POST['lnameL']);
    $query = "INSERT INTO `leaders`(`id_u`,`suname`,`name`,`lname`,`id_pos`,`id_sat`,`id_deg`,`arrival`,`phone`,`email`,`date`,`hash`)
                    VALUES
                    ('{$_POST['id_u']}','{$_POST['sunameL']}','{$_POST['nameL']}','{$_POST['lnameL']}','{$_POST['position']}','{$_POST['statusfull']}','{$_POST['degree']}','0','{$_POST['phoneL']}','{$_POST['emailL']}',NOW(),'{$hash}')";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    //print_r($query);
    $result = mysqli_query($link, $query);
    // or die("Полка запису дія leader_add: " . mysqli_error($link));
    if (mysqli_error($link)==''){
        $id_l = mysqli_insert_id($link);
        log_action($_POST['action'], "leaders", $id_l);
    }else{ // Выполнять если есть ошыбка
        $error_message .= AnalizeMysqlError(mysqli_error($link));
    }
}else{
    //присвоить если не равен 888
    $id_l = $_POST['id_l'];
}

//Если ошибок не возникло то можно попробывать связать работу
if('' == $error_message){

    $query = "INSERT INTO `wl` (`id_w`,`id_l`,`date`) VALUE ('{$id_w}','{$id_l}',NOW())";
    $result = mysqli_query($link, $query);
    log_action("work_link", "wl", $id_w);

    $query = "INSERT INTO `wa` (`id_w`,`id_a`,`date`) VALUE ('{$id_w}','{$id_a}',NOW())";
    $result = mysqli_query($link, $query);
    log_action("work_link", "wa", $id_w);
    // Перейдем и поспотрим результаты связывания
    $page = "action.php?action=view#id_w".$id_w;
}

Go_page($page);
?>