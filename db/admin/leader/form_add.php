<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:14
 */

use zukr\base\html\Html;
use zukr\base\html\HtmlHelper;
use zukr\degree\DegreeRepository;
use zukr\position\PositionRepository;
use zukr\status\StatusRepository;
use zukr\univer\UniverRepository;

$id_u = filter_input(INPUT_GET, 'id_u', FILTER_VALIDATE_INT);
$univers = (new UniverRepository())->getInvitedDropList();
$positions = (new PositionRepository())->getDropDownList();
$statuses = (new StatusRepository())->getDropDownList();
$degrees = (new DegreeRepository())->getDropDownList();
?>
<!-- Форма добавления керівника-->
<header><a href="action.php">Меню</a></header>
<header>Данні керівника</header>
<form class="addleaderForm" method="post" action="action.php">
    <?= Html::select('Leader[id_u]', $id_u, $univers,
        ['class' => 'select-univer', 'required' => true, 'prompt' => 'Університет...'])
    ?>
    <br>
    <label>ПІБ</label>
    <input type="text" name="Leader[suname]" title="Прізвище" placeholder="Прізвище" required>
    <input type="text" name="Leader[name]" title="Ім'я" placeholder="Ім'я" id="name" required>
    <input type="text" name="Leader[lname]" title="По-батькові" placeholder="По-батькові" id="lname" required>
    <label>Рецензент:</label>
    <?= HtmlHelper::checkbox('Leader[review]', 'Відмітити якщо це рецензент', 0) ?>
    <br>
    <br>
    <fieldset>
        <legend>Данні про керівника</legend>
        <?= Html::select('Leader[id_pos]', null, $positions,
            ['required' => true, 'prompt' => 'Посада...'])
        ?>
        <?= Html::select('Leader[id_sat]', 1, $statuses,
            ['required' => true, 'prompt' => 'Вчене звання...'])
        ?>
        <?= Html::select('Leader[id_deg]', 1, $degrees,
            ['required' => true, 'prompt' => 'Науковий ступінь...'])
        ?>
    </fieldset>
    <br>
    <label>Електронна скринька:</label>
    <input type="email" name="Leader[email]" title="Наприклад:user@mail.ru" placeholder="Електронна скринька">
    <label>Телефон:</label>
    <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="Leader[phone]" title="Наприклад:0985622012"
           placeholder="Номер телефону">
    <br>
    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="hidden" name="action" value="leader_add">
    <?php
    if (isset($_GET['id_w'])) {
        $id_w = (int)filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT);
        echo "<input type='hidden' name='id_w' value='{$id_w}'>";
    }
    ?>
</form>
