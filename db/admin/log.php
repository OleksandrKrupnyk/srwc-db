<?php
require 'config.inc.php';
require 'functions.php';
header('Content-Type: text/html; charset=utf-8');
session_name('tzLogin');
session_start();
global $link;

//Если есть доступ к странице
?>
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
<?php
if ($_SESSION['access']) {
    echo '<header><a href="action.php">Меню</a></header>';
    if (isset($_GET['view'])) {
        echo "<header>Данні {$_GET['view']}</header>"
            . '<header><a href="log.php" >Журнал</a></header>';
        $query = "SELECT tz_id, date,  DAYNAME(date) as `dayname`,   YEAR(date) as `year`,   MONTHNAME(date) AS `monthname`,
  TIME(date) AS `time`,   MONTH(date) AS `month`,   DAY(date) as `day`,   action, action_id,
  tz_members.usr as `operator` FROM log   JOIN tz_members ON log.tz_id=tz_members.id WHERE log.table = '{$_GET['view']}' ORDER BY log.date DESC";
        //echo $query;
        $result = mysqli_query($link, $query);
        echo '<table>'
            . '<tr><th>Хто</th><th>Коли</th><th>Що робив</th><th>З записом</th></tr>';
        $row = mysqli_fetch_array($result);
        $year = $row['year'];
        $month = $row['month'];
        echo "<tr><th colspan='4'>{$year}</th></tr>"
            . "<tr><th colspan='4'>{$row['monthname']}</th></tr>"
            . "<tr><td>{$row['operator']} ({$row['tz_id']})</td><td>{$row['dayname']},{$row['day']} {$row['time']}</td><td>{$row['action']} </td><td>{$row['action_id']}</td></tr>";
        while ($row = mysqli_fetch_array($result)) {
            if ($year != $row['year']) {
                $year = $row['year'];
                echo "<tr><th colspan='4'>{$year}</th></tr>";
            }
            if ($month != $row['month']) {
                $month = $row['month'];
                echo "<tr><th colspan='4'>{$row['monthname']}</th></tr>";
            }
            echo "<tr><td>{$row['operator']} ({$row['tz_id']})</td><td>{$row['dayname']},{$row['day']} {$row['time']}</td><td>{$row['action']}</td><td>{$row['action_id']}</td></tr>";
        }
        echo '</table>';
    } else {
        echo '<header>Журнал дій за таблицями</header>';
        $query = 'SELECT `table` FROM `log` GROUP BY `table`';
        $result = mysqli_query($link, $query);
        echo '<ol>';
        while ($row = mysqli_fetch_array($result)) {
            echo "<li><a href=log.php?view={$row['table']}>{$row['table']}</a></li>";
        }
        echo '</ol>';
    }
}//if
$query = "SELECT review1,
       COUNT(*) AS countReview,
       CONCAT(leaders.suname,' ',leaders.name,' ',leaders.lname)AS fio
FROM `reviews`
         JOIN leaders ON review1 = leaders.id
GROUP BY reviews.`review1`
ORDER BY countReview DESC";
$result = mysqli_query($link, $query);
$a = [];
while ($row = mysqli_fetch_array($result)) {
    $items[] = sprintf('<li>%s - %s</li>', $row['fio'], $row['countReview']);
    $a[] = $row['countReview'];
}
$query = http_build_query(['a' => $a]);
$sumCountReview = array_sum($a);
echo '<p>Статистика рецензування:</p><ol>' . implode('', $items) . '</ol>'
    . 'Всього рецензій : ' . $sumCountReview
    . '<br><img src="' . 'imgchart.php?' . $query . '" alt=\'рецензии\'>';
?>
</body>
</html>