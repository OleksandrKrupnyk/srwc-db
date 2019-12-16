<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:11
 */

use zukr\base\Base;
use zukr\base\html\Html;
use zukr\base\html\HtmlHelper;
use zukr\univer\UniverHelper;

$session = Base::$session;
$id_u = filter_input(INPUT_GET, 'id_u', FILTER_VALIDATE_INT);
$uh = UniverHelper::getInstance();
$univers = $uh->getInvitedDropdownList();

// redirect_to -> session
$session->setRedirectParam();
?>
<!-- Форма добавления автора-->
<header><a href="action.php">Меню</a></header>
<header>Данні автора</header>
<form class="addautorForm" method="post" action="action.php" name='Autor'>
    <?= Html::select('Author[id_u]', $id_u, $univers,
        ['id' => 'selunivers', 'required' => true, 'prompt' => 'Університет...', 'class' => 'w-100'])
    ?>
    <br>
    <label>ПІБ</label>
    <input type="text" name="Author[suname]" title="Прізвище" placeholder="Прізвище" required>
    <input type="text" name="Author[name]" title="Ім'я" placeholder="Ім'я" id="name" required>
    <input type="text" name="Author[lname]" title="По-батькові" placeholder="По-батькові" id="lname">
    <br>
    <fieldset>
        <legend>Данні автора</legend>
        <?= HtmlHelper::course(['name' => 'Author[curse]']) ?>
        <label>Електронна скринька:</label>
        <input type="email" name="Author[email]" title="Наприклад:user@mail.ru" placeholder="Электронна скринька">
        <label>Телефон:</label>
        <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="Author[phone]" title="Наприклад:0985622012"
               placeholder="Номер телефону">
    </fieldset>
    <br>
    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="hidden" name="action" value="autor_add">
    <?php
    if (isset($_GET['id_w'])) {
        $id_w = (int)filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT);
        echo "<input type='hidden' name='id_w' value='{$id_w}'>";
    }
    ?>
</form>