<?php

use zukr\base\Base;
use zukr\base\html\Html;

require 'config.inc.php';
require '../vendor/autoload.php';
session_name('tzLogin');
session_start();
Base::init();
//Если есть доступ к странице
$db = Base::$app->db;
if ($_SESSION['access']) {
    $content = '<header><a href="action.php">Меню</a></header>';
    if (isset($_GET['view'])) {
        $content .= "<header>Данні {$_GET['view']}</header><header><a href=\"log.php\" >Журнал</a></header>";
        $query = "
SELECT tz_id, 
       date,  
       DAYNAME(date) as `dayname`,   
       YEAR(date) as `year`,   
       MONTHNAME(date) AS `monthname`,
  TIME(date) AS `time`,   
       MONTH(date) AS `month`,   
       DAY(date) as `day`,   
       action, 
       action_id,
  tz_members.usr as `operator` 
FROM log   
    JOIN tz_members ON log.tz_id=tz_members.id 
WHERE log.table = '{$_GET['view']}' 
ORDER BY log.date DESC";
        $records = $db->rawQuery($query);
        $records = \zukr\base\helpers\ArrayHelper::index($records, null, ['year', 'monthname']);
        $table = '<table><tr><th>Хто</th><th>Коли</th><th>Що робив</th><th>З записом</th></tr>';
        foreach ($records as $year => $mouths) {
            $table .= "<tr><th colspan='4'>{$year}</th></tr>";
            foreach ($mouths as $mouth => $events) {
                $table .= "<tr><th colspan='4'>{$mouth}</th></tr>";
                foreach ($events as $event) {
                    $table .= "<tr><td>{$event['operator']} ({$event['tz_id']})</td><td>{$event['dayname']},{$event['day']} {$event['time']}</td><td>{$event['action']} </td><td>{$event['action_id']}</td></tr>";
                }
            }
        }
        $table .= '</table><hr/>';
        $content.= $table;
    } else {
        $records = $db->rawQuery("
SELECT `table` FROM `log` GROUP BY `table`
");
        $tables = [];
        foreach ($records as $record) {
            $tables[] = Html::a($record['table'], 'log.php?view=' . $record['table']);
        }
        $list = Html::ol($tables);
        $content .= <<<__HTML__
<header>Журнал дій за таблицями</header>
$list
__HTML__;
    }
}
$statistics = $db->rawQuery("
SELECT review1,
       COUNT(*) AS countReview,
       CONCAT(leaders.suname,' ',leaders.name,' ',leaders.lname)AS fio
FROM `reviews`
         JOIN leaders ON review1 = leaders.id
GROUP BY reviews.`review1`
ORDER BY countReview DESC;");
$a = [];
$reviewers = [];
foreach ($statistics as $reviewer) {
    $reviewers[] = $reviewer['fio'] . ' - ' . $reviewer['countReview'];
    $a[] = $reviewer['countReview'];
}
$list = Html::ol($reviewers);
$sumCountReview = array_sum($a);
$src = 'imgchart.php?' . http_build_query(['a' => $a]);
$contentStatistics = <<<__HTML__
<p>Статистика рецензування:</p>
{$list}
Всього рецензій : {$sumCountReview}
<br><img src="{$src}" alt='рецензии'>
__HTML__;

$html = <<<__HTML__
<!DOCTYPE html>
<html lang="ua">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="../css/style.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>
    <title>Журнал &quot;СНР 2020&quot;&copy;</title>
</head>
<body>
{$content}
{$contentStatistics}
</body>
</html>
__HTML__;
echo $html;


