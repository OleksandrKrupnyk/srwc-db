<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 10.11.17
 * Time: 23:27
 */
// Редактирование рецензии
global $link;
$_POST['defects'] = trim(addslashes($_POST['defects']));
$query = "UPDATE `reviews` SET 
`actual`='{$_POST['actual']}',
`original`='{$_POST['original']}',
`methods`='{$_POST['methods']}',
`theoretical`='{$_POST['theoretical']}',
`practical`='{$_POST['practical']}',
`literature`='{$_POST['literature']}',
`selfcontained`='{$_POST['selfcontained']}',
`design`='{$_POST['design']}',
`publication`='{$_POST['publication']}',
`government`='{$_POST['government']}',
`tendentious`='{$_POST['tendentious']}',
conclusion = '{$_POST['conclusion']}',
`defects` = '{$_POST['defects']}',
`review1` ='{$_POST['reviewer']}' ,
`date`=NOW()
WHERE `id`={$_POST['id']}";
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Полка оновлення запису дія review_edit: " . mysqli_error($link));
log_action($_POST['action'], "reviews", $_POST['id']);
header("Location: action.php?action=all_view#id_w" . $_POST['id_w']);
//$error_message = $query;
?>

