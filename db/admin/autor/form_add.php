<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:11
 */ ?>
<!-- Форма добавления автора-->
<header><a href="action.php">Меню</a></header>
<header>Данні автора</header>
<form class="addautorForm" method="post" action="action.php">
    <?php (isset($_GET['id_u'])) ? list_univers($_GET['id_u'], 1) : list_univers("", 1); ?>
    <br>
    <label>ПІБ</label>
    <input type="text" name="suname" title="Прізвище" placeholder="Прізвище" required>
    <input type="text" name="name" title="Ім'я" placeholder="Ім'я" id="name" required>
    <input type="text" name="lname" title="По-батькові" placeholder="По-батькові" id="lname">
    <br>
    <fieldset>
        <legend>Данні автора</legend>
        <select size="1" name="curse" title="Курс навчання">
            <option value="-1" disabled selected>Курс...</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
        </select>
        <label>Електронна скринька:</label>
        <input type="email" name="email" title="Наприклад:user@mail.ru" placeholder="Электронна скринька">
        <label>Телефон:</label>
        <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="phone" title="Наприклад:0985622012"
               placeholder="Номер телефону">
    </fieldset>
    <br>
    <input type="submit" value="Записати">
    <input type="hidden" name="action" value="autor_add">
    <?php
    if (isset($_GET['id_w'])) {
        $id_w = (int)filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT);
        echo "<input type='hidden' name='id_w' value='{$id_w}'>";
    }
    print_datalist_name("name");
    print_datalist_name("lname"); ?>
</form>