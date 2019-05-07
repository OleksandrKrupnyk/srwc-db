<header><a href="action.php">Меню</a></header>
    <header>Зв'язування роботи</header>
    <form class="linkworkForm" method="post" action="action.php">
        <?php
        if (isset($_GET['id_w'])) {
            $wInfo = fullinfo("works", "id", $_GET['id_w']);
            list_univers($wInfo['id_u'], "10");
            echo "<script language=\"javascript\" type=\"text/javascript\">selectWork(" . $wInfo['id_u'] . "," . $_GET['id_w'] . ");</script>";
        } else {
            list_univers("", "10");
        }
        ?>
        <div id="work"></div>
        <table id="table_la">
            <tr>
                <th>Керівники</th>
                <th>Автори</th>
            </tr>
            <tr>
                <td id="leader"></td>
                <td id="autor"></td>
            </tr>
            <tr>
                <td id="leaders"></td>
                <td id="autors"></td>
            </tr>
        </table>
        <input type="submit" value="Записати">
        <input type="hidden" name="action" value="work_link">
    </form>
    <!-- Окончание Связывание работы -->