<?php

use zukr\author\AuthorRepository;
use zukr\base\html\Html;
use zukr\base\html\HtmlHelper;
use zukr\univer\UniverRepository;

/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:17
 *
 */
$id_a = filter_input(INPUT_GET, 'id_a', FILTER_VALIDATE_INT);

$author = (new AuthorRepository())->getById($id_a);
if (empty($author)) {
    Go_page('action.php?action=error_list');
    exit();
}
$univers = (new UniverRepository())->getInvitedDropList();
?>
<!-- Редактирование автора -->
<header><a href="action.php">Меню</a></header>
<header>Редагування данних автора</header>
<form class="editAutor" method="post" action="action.php">
    <?= Html::select('Author[id_u]', $author['id_u'], $univers,
        ['id' => 'selunivers', 'required' => true, 'prompt' => 'Університет...'])
    ?>
    <br>
    <label>ПІБ</label>
    <input type="text" name="Author[suname]" title="Прізвище" value="<?= $author['suname'] ?>" required>
    <input type="text" name="Author[name]" title="Ім'я" value="<?= $author['name'] ?>" required>
    <input type="text" name="Author[lname]" title="По-батькові" value="<?= $author['lname'] ?>">
    <label>Місце:</label><?= $author['arrival'] === '1'
        ? HtmlHelper::place(['name' => 'Author[place]', 'value' => $author['place']])
        : 'Не брав участі'
    ?><br>
    <fieldset>
        <legend>Данні автора</legend>
        <?= HtmlHelper::course(['name' => 'Author[curse]', 'value' => $author['curse']]) ?>
        <label>Електронна скринька:</label>
        <input type="email" name="Author[email]" autocomplete="off" title="Наприклад:user@mail.ru"
               value="<?= $author['email'] ?>">
        <label>Телефон:</label>
        <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="Author[phone]" title="Наприклад:0985622012"
               value="<?= $author['phone'] ?>" placeholder="Номер телефону">
        <?php
        $phone_number = $author['phone'] === '' ? 'відсутній' : $author['phone'];
        echo "<span id='phone'>{$phone_number}</span>";
        ?>
        <br>
        <label>Прибув?</label>
        <?= HtmlHelper::checkbox('Author[arrival]', 'Відмітка про прибуття на конференцію', $author['arrival']) ?>
        <label>Не друкувати
            бейджик</label><?= HtmlHelper::checkbox('Author[bprint]', 'Заборона на друк бейджика', $author['bprint']) ?>
    </fieldset>
    <br>

    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="hidden" name="action" value="autor_edit">
    <input type="hidden" name="Author[id]" value="<?= $author['id'] ?>">
    <input type="hidden" name="id_a" value="<?= $id_a ?>">
    <input type="hidden" name="from" value="<?= $_GET['FROM'] ?>">
</form>
