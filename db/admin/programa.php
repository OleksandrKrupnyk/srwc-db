<?php

/**
 * @author    study
 * @copyright 2017
 */

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\section\SectionHelper;
use zukr\univer\UniverHelper;
use zukr\work\WorkHelper;

require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';
header("Content-Type: text/html; charset=utf-8");
session_name('tzLogin');
session_start();
Base::init();
$session = Base::$session;
$settings = Base::$param;
//Если есть доступ к странице
if (Base::$user->getUser()->isGuest()) {
    Go_page('index.php');
}
$wh = WorkHelper::getInstance();
$sh = SectionHelper::getInstance();
$sections = $sh->getAllSections();

$uh = UniverHelper::getInstance();
$univerList = $uh->getUnivers();
$sectionList = ArrayHelper::group($wh->getInvitationWorks(), 'id_sec');
?>
<!DOCTYPE html>
<html lang="ua">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="../css/print.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>
    <title>Программа підсумкової конференції</title>
</head>
<body>
<table class="tableprogramma">
    <?php
    $sectionCounter = 1;
    foreach ($sectionList as $id_sec => $works) {
        echo '<tr><th colspan="5"> Секція №' . $sectionCounter . '. Аудиторія ' . $sections[$id_sec]['room'] . ' <br>' . $sections[$id_sec]['section'] . '</th></tr>'
            . '<tr><th>№</th><th>Доповідь</th><th>Доповідач(i)/<br>Ширф автора</th><th>Керівник</th><th>ВНЗ</th></tr>';
        $i = 1;
        uasort($works, function ($a, $b) {
            return $a['title'] <=> $b['title'];
        });
        foreach ($works as $id_w => $w) {
            $leaders = $wh->getListLeadersForProgramaByWorkId($w['id']);
            $authors = $wh->getListAuthorsForProgramaByWorkId($w['id']);
            echo "<tr>
<td>{$i}.</td>
<td>{$w['title']}</td>
<td class='tda'>{$authors}</td>
<td class='tdl'>{$leaders}</td>
<td class='tdu'>" . $univerList[$w['id_u']]['univer'] . "</td></tr>";
            $i++;
        }
        $sectionCounter++;
    }
    ?>
</table>
</body>
</html>