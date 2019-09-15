<?php
/** ВИбір аудиторії
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:38
 */
global $link;
    $query = "SELECT sections.*, (SELECT COUNT(id_sec) FROM works WHERE id_sec = sections.id AND invitation='1' AND arrival = '1' ) as count  FROM sections";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query);
    ?>
<!-- Роспеределение секций по аудиториям-->
<header><a href="action.php">Меню</a></header>
<header>Аудиторії</header>
<table id='tableSelectRoom'>
<tr><th>Шифр<br/> секції</th><th>Назва секції</th><th>Аудиторія</th><th>Робіт</th></tr>
    <?php
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr><td>{$row['id']}</td><td>{$row['section']}</td>";
        echo "<td>";
        echo select_room($row['room']);
        echo "</td><td>{$row['count']}</td></tr>";
    }
    echo "</table>\n";

    ?>
<!-- Окончание распределения секций по аудиториям-->