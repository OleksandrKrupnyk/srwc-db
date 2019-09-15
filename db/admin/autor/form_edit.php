<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:17
 *
 */
$aInfo = fullinfo('autors', 'id', $_GET['id_a']);
?>
<!-- Редактирование автора -->
<header><a href="action.php">Меню</a></header>
<header>Редагування данних автора</header>
<form class="editAutor" method="post" action="action.php">
    <?php list_univers($aInfo['id_u'], 1); ?>
    <br>
    <label>ПІБ</label>
    <input type="text" name="suname" title="Прізвище" value="<?= $aInfo['suname'] ?>" required>
    <input type="text" name="name" title="Ім'я" value="<?= $aInfo['name'] ?>" required>
    <input type="text" name="lname" title="По-батькові" value="<?= $aInfo['lname'] ?>">
    <label>Місце:</label>
    <?php
    if($aInfo['arrival']==1){
        cbo_place($aInfo['place']);
    } else {
        echo "Не брав участі";

    }
    ?>
    <br>
    <fieldset>
        <legend>Данні автора</legend>
        <?php cbo_curse($aInfo['curse']); ?>
        <label>Електронна скринька:</label>
        <input type="email" name="email" autocomplete="off" title="Наприклад:user@mail.ru"
               value="<?= $aInfo['email'] ?>">
        <label>Телефон:</label>
        <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="phone" title="Наприклад:0985622012"
               value="<?= $aInfo['phone'] ?>" placeholder="Номер телефону">
        <?php
        $phone_number = ($aInfo['phone'] == '') ? 'відсутній' : $aInfo['phone'];
        echo "<span id='phone'>{$phone_number}</span>";
        ?>
        <br>
        <label>Прибув?</label><?php chk_box('arrival', 'Відмітка про прибуття на конференцію', $aInfo['arrival']); ?>
        <label>Недрукувати бейджик</label><?php chk_box('bprint', 'Заборона на друк бейджика', $aInfo['bprint']); ?>
    </fieldset>
    <br>

    <input type="submit" value="Змінити">
    <input type="hidden" name="action" value="autor_edit">
    <input type="hidden" name="id_a" value="<?= $aInfo['id'] ?>">
    <input type="hidden" name="from" value="<?= $_GET['FROM'] ?>">
</form>
