<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:39
 */
// Добавление рецензии
global $link;
$id_w = filter_input(INPUT_POST, 'id_w',FILTER_VALIDATE_INT);

var_dump($_POST);die();

/* Если рецензий в БД менше 2 то вводить*/
if (count_review($id_w) < 2) {
    $_POST['defects'] = trim(addslashes($_POST['defects']));
    $query = "INSERT INTO `reviews` 
         (`id_w`,`actual`,`original`,`methods`,`theoretical`,`practical`,`literature`,`selfcontained`,`design`,`publication`,`government`,`tendentious`,`defects`,`conclusion`,`review1`,`date`)
         VALUES 
         ('{$_POST['id_w']}','{$_POST['actual']}','{$_POST['original']}','{$_POST['methods']}','{$_POST['theoretical']}','{$_POST['practical']}','{$_POST['literature']}','{$_POST['selfcontained']}','{$_POST['design']}','{$_POST['publication']}','{$_POST['government']}','{$_POST['tendentious']}','{$_POST['defects']}','{$_POST['conclusion']}','{$_POST['reviewer']}',NOW())";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query);
    //echo "<pre>";print_r($_POST);print_r($query);echo "</pre>";
    echo mysqli_error($link);
    if (mysqli_error($link) == '') {
        $id = mysqli_insert_id($link);
        log_action($_POST['action'], "reviews", $id);
        Go_page("action.php?action=all_view#id_w{$_POST['id_w']}");
    } else { // Выполнять если есть ошыбка
        $error_message .= AnaliseMysqlError(mysqli_error($link));
    }
}else{
    $error_message .= "На роботу вже є 2 рецензії";
}
?>