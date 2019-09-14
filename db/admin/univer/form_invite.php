<header>
    <a href='action.php'>Меню</a></header>
<header>Список университетів</header>
<table id='tableInviteUnivers'>
    <tr>
        <th>№</th>
        <th>Університет</th>
        <th>?</th>
    </tr>
    <?php
    global $link;
    $query = "SELECT id,univerfull, invite FROM  univers ORDER BY univerfull ASC ";
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_query($link, "SET CHARACTER SET 'utf8'");
    //посылаем запрос
    $result = mysqli_query($link, $query)
    or die("Invalid query функція action=univer_invite: " . mysqli_error($link));
    //первый запрос
    $i = 1;
    while ($row = mysqli_fetch_array($result)) {
        $str1 = "<tr><td>{$i}</td>";
        $str1 .= "<td><a href='action.php?action=univer_edit&id_u={$row['id']}&FROM=action.php?action=univer_invite'>{$row['univerfull']}</a></td>";
        echo $str1 . '<td>';
        chk_box("invitation", "", $row['invite']);
        echo "<input type='hidden' name='id_u' value='{$row['id']}'></td></tr>";
        $i++;
    }
    ?>
</table><a href='lists.php?list=adress'><input type='button' value='Друкувати список'></a>
