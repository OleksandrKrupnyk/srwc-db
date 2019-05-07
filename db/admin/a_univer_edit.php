<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:02
 */

//print_r($_POST);
$_POST['univer'] = trim(addslashes($_POST['univer']));
$_POST['univerfull'] = trim(addslashes($_POST['univerfull']));
$_POST['univerrod'] = trim(addslashes($_POST['univerrod']));
$_POST['zipcode'] = (string)trim($_POST['zipcode']);
$_POST['adress'] = trim(addslashes($_POST['adress']));
$_POST['rector_r'] = trim(addslashes($_POST['rector_r']));
$_POST['posada'] = trim(addslashes($_POST['posada']));
$_POST['http'] = htmlspecialchars(trim($_POST['http']));
$_POST['town'] = htmlspecialchars(trim($_POST['town']));
$query = "UPDATE `univers` SET `univer`='{$_POST['univer']}',`univerfull`='{$_POST['univerfull']}',`univerrod`='{$_POST['univerrod']}',`zipcode`='{$_POST['zipcode']}', `town`='{$_POST['town']}',
                        `adress`='{$_POST['adress']}',`rector_r`='{$_POST['rector_r']}',`posada`='{$_POST['posada']}',`http`='{$_POST['http']}'\n"
    . "WHERE `id`='{$_POST['id']}'";
//echo $query;
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query)
or die("Полка оновлення запису дія univer_edit: " . mysqli_error($link));
log_action($_POST['action'], "univers", $_POST['id']);
$url2go = ($_POST['from']) ? $_POST['from'] : "action.php?action=view";
$url2go = ($_GET['from']) ? $_GET['from'] : $url2go;
header("Location:" . $url2go);
//    header("Location: action.php?action=view");
?>