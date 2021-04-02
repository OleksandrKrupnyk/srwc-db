<?php
require 'config.inc.php';
require 'functions.php';
header('Content-Type: text/html; charset=utf-8');
global $link;
$query = ($_GET['t'] === "l")?"UPDATE `leaders` ": "UPDATE `autors` ";
$query .= "SET `email_recive`= TRUE, `email_date`=NOW() WHERE `hash` = '{$_GET['hash']}'";
$result = mysqli_query($link, $query) or die("Invalid query Файл обробки підтверджень відповідей: " . mysqli_error($link));
//Заменить на другой адрес
Go_page("../app/");