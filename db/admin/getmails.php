<?php
require 'config.inc.php';
require 'functions.php';
header("Content-Type: text/html; charset=utf-8");
global $link;
$query = ($_GET['t'] == "l")?"UPDATE `leaders` ": "UPDATE `autors` ";
$query .= "SET `email_recive`= TRUE, `email_date`=NOW() WHERE `hash` = '{$_GET['hash']}'";
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query) or die("Invalid query Файл обробки підтверджень відповідей: " . mysqli_error($link));
//Заменить на другой адресс
Go_page("../app/");
//header("Location: action.php?action=sentemail");
?>