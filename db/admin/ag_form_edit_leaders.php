<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:19
 */?>
<?php $lInfo = fullinfo("leaders", "id", $_GET['id_l']); /*получим все данные о руководителе*/ ?>
<header><a href="action.php">Меню</a></header>
<header>Редагування данних керівника</header>
<form class="editLeader" method="post" action="action.php">
    <?php list_univers($lInfo['id_u'], 1); ?>
    <br><label>ПІБ</label>
    <input type="text" name="suname" title="Прізвище" value="<?= $lInfo['suname'] ?>" required>
    <input type="text" name="name" title="Ім'я" value="<?= $lInfo['name'] ?>" required>
    <input type="text" name="lname" title="По-батькові" value="<?= $lInfo['lname'] ?>" required>
    <br><label>Рецензент:</label>
    <?php chk_box("reviewer", "Відмітити якщо рецензент", $lInfo['review']); ?>
    <?php if($_SESSION['usr'] == "krupnik"){
        printf("<label>Логін користувача у системі для рецензування:</label>");
        //printf("TODO");
        //printf("<label>Логін:%s</label>",$lInfo['id_tzmember']);
        //Сформировать запрос
        $query = "SELECT tz_members.* FROM tz_members WHERE usr <> 'AJAX' GROUP BY tz_members.id ASC ";
        //Отправить запрос
        mysqli_query($link, "SET NAMES 'utf8'");
        mysqli_query($link, "SET CHARACTER SET 'utf8'");
        $result = mysqli_query($link, $query)
        or die("Invalid query: " . mysqli_error($link));
        printf("<select name=\"tzmember\" id=\"tzmember\">\n");
            printf("<option value=\"%s\">%s</option>", "0", "Відсутній");
            while ($row = mysqli_fetch_array($result)) {
                if ($lInfo['id_tzmember'] != '' AND !is_integer($lInfo['id_tzmember'])) {
                    if($lInfo['id_tzmember'] == $row['id']){
                        printf("<option value=\"%s\" selected>%s</option>", $row['id'], $row['usr']);
                    }else
                        printf("<option value=\"%s\">%s</option>", $row['id'], $row['usr']);
                }
                else{
                    printf("<option value=\"%s\">%s</option>", $row['id'], $row['usr']);
                }
            }


        printf("</select>\n");
    }?>
    <br>
    <fieldset>
        <legend>Данні про керівника</legend>
        <?php list_("positions", "position", $lInfo['id_pos'], 1, "Посада...") ?>
        <?php list_("statuses", "statusfull", $lInfo['id_sat'], 1, "Вчене звання...") ?>
        <?php list_("degrees", "degree", $lInfo['id_deg'], 1, "Науковий ступінь...") ?>
        <?php chk_box("arrival", "Відмітка про прибуття на конференцію", $lInfo['arrival']); ?>
        <label>Електронна скринька:</label>
        <input type="email" name="email" autocomplete="off" value="<?= $lInfo['email'] ?>" placeholder="user@mail.ru">
        <?php
        $phone_number = ($lInfo['phone'] == "") ? "відсутній" : $lInfo['phone'];
        echo "<span id=\"phone\">{$phone_number}</span>";
        ?>
        <label>Телефон:</label>
        <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="phone" value="<?= $lInfo['phone'] ?>"
               placeholder="Номер телефону">
        <br>

    </fieldset>
    <input type="submit" value="Змінити">
    <input type="hidden" name="action" value="leader_edit">
    <input type="hidden" name="id_l" value="<?= $lInfo['id'] ?>">
    <input type="hidden" name="from" value="<?= $_GET['FROM'] ?>">
</form>
