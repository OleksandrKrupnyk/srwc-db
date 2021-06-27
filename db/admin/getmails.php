<?php

use zukr\base\Base;

require 'config.inc.php';
require '../vendor/autoload.php';
header('Content-Type: text/html; charset=utf-8');
Base::init();
$db = Base::$app->db;
$hash = $_GET['hash'];
$query = (string)$_GET['t'] === "l"
    ? "UPDATE `leaders` SET `email_recive`= TRUE, `email_date`=NOW() WHERE `hash` = $hash;"
    : "UPDATE `autors` SET `email_recive`= TRUE, `email_date`=NOW() WHERE `hash` = $hash;";
$db->rawQuery($query);
//Заменить на другой адрес
Go_page("../app/");