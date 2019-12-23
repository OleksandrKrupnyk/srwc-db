<?php
function list_univers_reseption($size)
{
    global $link;
    $query = "SELECT `univers`.`id`,`univers`.`univer`,`univers`.`univerfull` FROM `univers` RIGHT OUTER JOIN `works` ON  `works`.`id_u` = `univers`.`id` GROUP BY univer ORDER BY univer";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    $result = mysqli_query($link, $query)
    or die('Invalid query функція list_univers_reseption: ' . mysqli_error($link));
    echo '<select id="univer_reseption" name="id_u" size="' . $size . '" class="w-100">
            <option value="-1" disabled selected>Університет...</option>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<option value=' . $row['id'] . '>' . $row['univer'] . ' (' . $row['univerfull'] . ")</option>\n";
    }
    echo "</select>\n";
}

?>
<!-- Отметки на регистрации в 3-м корпусе -->
<!-- Отметки о прибытии на конкурс  -->
<div class="layout">
    <header><a href="action.php">Меню</a></header>
    <header id="update_arrival_works" title="Подвійне клацання для оновлення відміток у таблиці робіт">Реестрація
        учасників конференції
    </header>
    <?php list_univers_reseption(10); ?>
    <div style="display : flex;justify-content: space-around;align-items: stretch">
        <div id="columnAutors"><label>Автори (тільки запрошені)</label>
            <ol id="selectAutors"></ol>
        </div>
        <div id="columnLeaders"><label>Супроводжуючі (усі)</label>
            <ol id="selectLeaders"></ol>
        </div>
    </div>
</div>
