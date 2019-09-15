<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:14
 */
?>
<!-- Форма добавления керівника-->
<header><a href="action.php">Меню</a></header>
<header>Данні керівника</header>
<form class="addleaderForm" method="post" action="action.php">
    <label>Університет:</label>
    <?php
    (isset($_GET['id_u'])) ? list_univers($_GET['id_u'], 1) : list_univers("", 1);
    ?>
    <br>
    <label>ПІБ</label>
    <input type="text" name="suname" title="Прізвище" placeholder="Прізвище" required>
    <input type="text" name="name" title="Ім'я" placeholder="Ім'я" id="name" required>
    <input type="text" name="lname" title="По-батькові" placeholder="По-батькові" id="lname" required>
    <label>Рецензент:</label> <input type="checkbox" name="reviewer" title="Відмітити якщо це рецензент"><br>
    <br>
    <fieldset>
        <legend>Данні про керівника</legend>
        <?php list_("positions", "position", "", 1, "Посада...") ?>
        <?php list_("statuses", "statusfull", "", 1, "Вчене звання...") ?>
        <?php list_("degrees", "degree", "", 1, "Науковий ступінь...") ?>

    </fieldset>
    <br>
    <label>Електронна скринька:</label>
    <input type="email" name="email" title="Наприклад:user@mail.ru" placeholder="Електронна скринька">
    <label>Телефон:</label>
    <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="phone" title="Наприклад:0985622012"
           placeholder="Номер телефону">
    <br>
    <input type="submit" value="Записати">
    <input type="hidden" name="action" value="leader_add">
    <?php
    print_datalist_name("name");
    print_datalist_name("lname");
    ?>
</form>
