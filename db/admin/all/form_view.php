<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:24
 */

$viewMenuitem[0] = "<li class=\"active2\"><a href=\"#\">Всі</a></li>\n";
$viewMenuitem[1] = "<li class=\"inactive\"><a href=\"action.php?action=all_view&who=invitation\">Запрошені</a></li>\n";
$viewMenuitem[2] = "<li class=\"inactive\"><a href=\"action.php?action=all_view&who=tesis\">З тезами</a></li>\n";
$viewMenuitem[3] = "<li class=\"inactive\"><a href=\"action.php?action=all_view&who=arrival\">Прибули</a></li>\n";
$viewMenuitem[4] = "<li class=\"inactive\"><a href=\"action.php?action=all_view&who=introduction\">Впроваджені</a></li>\n";
$viewMenuitem[5] = "<li class=\"inactive\"><a href=\"action.php?action=all_view&who=public\">З публікацією</a></li>\n";
$viewMenuitem[6] = "<li class=\"inactive\"><a href=\"action.php?action=all_view&who=comments\">Примітки</a></li>\n";
$viewMenuitem[7] = "<li class=\"inactive\"><a href=\"action.php?action=all_view&who=raiting\">За рейтингом</a></li>\n";


if (isset($_GET['who'])) {
    $viewMenuitem[0] = "<li class='inactive'><a href='action.php?action=all_view'>Всі</a></li>".PHP_EOL;
    switch ($_GET['who']) {
        case "invitation": {
            $query = "SELECT * FROM works WHERE invitation = 1 GROUP BY id_u,title";
            $viewMenuitem[1] = "<li class='active2'><a href='#'>Запрошені</a></li>\n";
        }
            break;
        case "tesis": {
            $query = "SELECT * FROM works WHERE tesis = 1 GROUP BY id_u,title";
            $viewMenuitem[2] = "<li class=\"active2\"><a href=\"#\">З тезами</a></li>\n";
        }
            break;
        case "arrival": {
            $query = "SELECT *  FROM works WHERE arrival = '1' GROUP BY id_u,title";
            $viewMenuitem[3] = "<li class=\"active2\"><a href=\"#\">Прибули</a></li>\n";
        }
            break;
        case "introduction": {
            $query = "SELECT *  FROM works WHERE introduction <> '' GROUP BY id_u,title";
            $viewMenuitem[4] = "<li class=\"active2\"><a href=\"#\">Впроваджені</a></li>\n";
        }
            break;
        case "public": {
            $query = "SELECT *  FROM works WHERE public <> '' GROUP BY id_u,title";
            $viewMenuitem[5] = "<li class=\"active2\"><a href=\"#\">З публікацією</a></li>\n";
        }
            break;
        case "comments": {
            $query = "SELECT *  FROM works WHERE comments <> '' GROUP BY id_u,title";
            $viewMenuitem[6] = "<li class=\"active2\"><a href=\"#\">Примітки</a></li>\n";
        }
            break;
        case "raiting": {
            $query = "SELECT works.*  FROM works  ORDER BY balls DESC ";
            $viewMenuitem[7] = "<li class=\"active2\"><a href=\"#\">За рейтингом</a></li>\n";
        }
            break;
    }
} else
    $query = "SELECT  works.*, univers.univerfull FROM  works JOIN  univers ON  univers.id = works.id_u GROUP BY  univerfull,title";

mysqli_query($link, "SET NAMES 'utf8'");
mysqli_query($link, "SET CHARACTER SET 'utf8'");
$result = mysqli_query($link, $query);
$count = mysqli_num_rows($result);
?>
<!-- Просмотр базы -->
<header><a href="action.php">Меню</a></header>
<header>Перегляд бази (<?= $count ?> робіт)</header>
<h1><a href="./invitation.php">=[ Запрошення ]=</a></h1>
<menu class="viewTableMenu">
    <?php  vprintf("%s %s %s %s %s %s %s %s",$viewMenuitem);  ?>
</menu>


<div id="viewtable">
    <table>
        <tr>
            <th>id<br/>номер</th>
            <th class="title">Назва роботи</th>
            <th class="title">Рецензія</th>
            <th>Керівникі</th>
            <th>Автори<номер>(місце)[приїхав]</th>
        </tr>

        <?php
        ob_start(); //включение буфера вывода

        if ($count != 0) {
            $row = mysqli_fetch_array($result);
            $univer = fullinfo("univers", "id", $row['id_u']);
            print_work_univer($univer['univerfull'], $univer['id'], $univer['univer'], true);
            print_work_row($row, true,$_SESSION['id']);
            while ($row = mysqli_fetch_array($result)) {
                if ($row['id_u'] != $univer['id']) {
                    $univer = fullinfo("univers", "id", $row['id_u']);
                    print_work_univer($univer['univerfull'], $univer['id'], $univer['univer'], true);
                }
                print_work_row($row, true,$_SESSION['id']);
            }
        }

        echo ob_get_clean(); // вывод содержимого буффера на экран
        ?>

    </table>
</div>
<div id="barUnivers"></div>
<!-- Окончание Просмотр базы -->