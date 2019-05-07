<?php
require 'config.inc.php';
require 'functions.php';
header("Content-Type: text/html; charset=utf-8");
session_name('tzLogin');
session_start();
global $link;
//Если есть доступ к странице
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="../css/style.css" type="text/css" rel="stylesheet"/>
    <link href="../css/jquery-ui-1.10.3.custom.min.css" type="text/css" rel="stylesheet"/>
    <script language="javascript" type="text/javascript" src="../js/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="../js/jquery-ui-1.10.js"></script>
    <script language="javascript" type="text/javascript" src="../js/admin.js"></script>
    <title>Журнал &quot;СНР 2018&quot;&copy;</title>
</head>
<body>
<?php
if ($_SESSION['access']) {
    echo "<header><a href=\"action.php\">Меню</a></header>";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    if (isset($_GET['view'])) {
        echo "<header>Данні {$_GET['view']}</header>";
        echo "<header><a href=\"log.php\" >Журнал</a></header>";
        $query = "SELECT tz_id, date,  DAYNAME(date) as `dayname`,   YEAR(date) as `year`,   MONTHNAME(date) AS `monthname`,
  TIME(date) AS `time`,   MONTH(date) AS `month`,   DAY(date) as `day`,   action, action_id,
  tz_members.usr as `operator` FROM log   JOIN tz_members ON log.tz_id=tz_members.id WHERE log.table = '{$_GET['view']}' ORDER BY log.date DESC";
        //echo $query;
        $result = mysqli_query($link, $query);
        echo "<table>";
        echo "<tr><th>Хто</th><th>Коли</th><th>Що робив</th><th>З записом</th></tr>";
        $row = mysqli_fetch_array($result);
        $year = $row['year'];
        echo "<tr><th colspan=\"4\">{$year}</th></tr>";
        $month = $row['month'];
        echo "<tr><th colspan=\"4\">{$row['monthname']}</th></tr>";
        echo "<tr><td>{$row['operator']} ({$row['tz_id']})</td><td>{$row['dayname']},{$row['day']} {$row['time']}</td><td>{$row['action']} </td><td>{$row['action_id']}</td></tr>";
        while ($row = mysqli_fetch_array($result)) {
            if ($year != $row['year']) {
                $year = $row['year'];
                echo "<tr><th colspan=\"4\">{$year}</th></tr>";
            }
            if ($month != $row['month']) {
                $month = $row['month'];
                echo "<tr><th colspan=\"4\">{$row['monthname']}</th></tr>";
            }
            echo "<tr><td>{$row['operator']} ({$row['tz_id']})</td><td>{$row['dayname']},{$row['day']} {$row['time']}</td><td>{$row['action']}</td><td>{$row['action_id']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<header>Журнал дій за таблицями</header>";
        $query = "SELECT log.table FROM log GROUP BY log.table ASC";
        $result = mysqli_query($link, $query);
        echo "<ol>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<li><a href=log.php?view={$row['table']}>{$row['table']}</a></li>";
        }
        echo "</ol>";
    }
}//if
$query = "SELECT  tz_members.usr, CONCAT(leaders.suname,' ',leaders.name,' ',leaders.lname) AS fio,COUNT(tz_id) AS countReview
FROM log
JOIN tz_members ON tz_members.id = log.tz_id
JOIN leaders ON tz_members.id = leaders.id_tzmember
WHERE `table` = 'reviews' GROUP BY usr ORDER BY countReview DESC";
mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query);
$sumCountReview = 0;
$a = array();
$f = array();

printf("<p>Статистика рецензування:</p><ol>");
while ($row = mysqli_fetch_array($result)) {
    printf("<li>%s - %s</li>", $row['fio'], $row['countReview']);
    array_push($a,$row['countReview']);
    array_push($f,$row['fio']);
}
printf("</ol>");

$sumCountReview = array_sum($a);
$strFormat = "imgchart.php?";
$strArrayFormat1 = array();
$strArrayFormat2 = array();

foreach ($a as $element){
    array_push($strArrayFormat1, "a[]=%s");
    array_push($strArrayFormat2, "f[]=%s");
}
$strFormat .= implode("&",$strArrayFormat1);
$strFormat .= "&";
$strFormat .= implode("&",$strArrayFormat2);
printf("Всього рецензій : %s", $sumCountReview);
echo "<br><img src=\"".vsprintf($strFormat,array_merge($a,$f))."\" >";
?>
</body>
</html>