<?php
/** ВИбір аудиторії
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:38
 */
    printf("<header><a href=\"action.php\">Меню</a></header><header>Аудиторії</header>\n");
    $query = "SELECT sections.*, (SELECT COUNT(id_sec) FROM works WHERE id_sec = sections.id AND invitation='1' AND arrival = '1' ) as count  FROM sections";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query);
    echo "<table id=\"tableSelectRoom\">\n";
    echo "<tr><th>Шифр<br/> секції</th><th>Назва секції</th><th>Аудиторія</th><th>Робіт</th></tr>\n";
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td><td>{$row['section']}</td>";
        echo "<td>";
        select_room($row['room']);
        echo "</td><td>{$row['count']}</td>";
        echo "</tr>";
    }
    echo "</table>\n";

    ?>
<!-- Окончание распределения секций по аудиториям-->