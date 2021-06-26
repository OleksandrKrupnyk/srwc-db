<?php
/**
 * @author    Krupnik
 * @copyright 2018
 */


require './../admin/config.inc.php';
require './../admin/functions.php';
require '../vendor/autoload.php';

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\Params;
use zukr\section\SectionHelper;
use zukr\univer\UniverHelper;
use zukr\work\WorkHelper;

header('Content-Type: text/html; charset=utf-8');
Base::init();
if (Params::TURN_ON !== Base::$param->SHOW_PROGRAMA) {
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
    <title>Реєст <?= Base::$app->app_name ?> / Программа підсумкової конференції</title>
</head>
<body class="programa">
<h1>Программа підсумкової науково-практичної конференції</h1>
<h1>Всеукраїнського конкурсу студентських наукових робіт</h1>
<h1>з галузі</h1>
<h1>&quot;Електротехніка та електромеханіка&quot;</h1>
<table class="tableprogramma">
    <?php
    $sectionCounter = 1;
    foreach ($sectionList as $id_sec => $works) {
        echo '
<tr>
<th colspan="5"> 
Секція №' . $sectionCounter . '. Аудиторія ' . $sections[$id_sec]['room'] . ' <br>' . $sections[$id_sec]['section'] .'<br/>' .$sections[$id_sec]['link']??''.'
</th></tr>'
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
    } ?>
</table>

</body>
</html>