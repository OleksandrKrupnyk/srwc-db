<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:19
 */


use zukr\base\Base;
use zukr\base\html\Html;
use zukr\base\html\HtmlHelper;
use zukr\degree\DegreeRepository;
use zukr\leader\LeaderRepository;
use zukr\position\PositionRepository;
use zukr\status\StatusRepository;
use zukr\univer\UniverHelper;
use zukr\user\UserRepository;

$id_l = filter_input(INPUT_GET, 'id_l', FILTER_VALIDATE_INT);
/** @var array */
$leader = (new LeaderRepository())->getById($id_l);
if (empty($leader) || !$id_l) {
    Go_page('error');
}
$uh = UniverHelper::getInstance();
$univers = $uh->getInvitedDropdownList();

$positions = (new PositionRepository())->getDropDownList();
$statuses = (new StatusRepository())->getDropDownList();
$degrees = (new DegreeRepository())->getDropDownList();
$users = (new UserRepository())->getDropDownList();
// redirect_to -> session
Base::$session->setRedirectParam();
?>
<!-- Редактирование руководителя -->
<header><a href="action.php">Меню</a></header>
<header>Редагування данних керівника</header>
<form class="editLeader form" method="post" action="action.php">
    <?= Html::select('Leader[id_u]', $leader['id_u'], $univers,
        ['class' => 'w-100', 'required' => true, 'prompt' => 'Університет...'])
    ?>
    <br><label>ПІБ</label>
    <input type="text" name="Leader[suname]" title="Прізвище" value="<?= $leader['suname'] ?>" required>
    <input type="text" name="Leader[name]" title="Ім'я" value="<?= $leader['name'] ?>" required>
    <input type="text" name="Leader[lname]" title="По-батькові" value="<?= $leader['lname'] ?>" required>
    <br><label>Рецензент:</label>
    <?= HtmlHelper::checkbox('Leader[review]', 'Відмітка про прибуття на конференцію', $leader['review']) ?>

    <?php if (Base::$user->getUser()->isAdmin()): ?>
        <label>Логін користувача у системі для рецензування:</label>
        <?= Html::select('Leader[id_tzmember]', $leader['id_tzmember'], $users,
            ['class' => 'select-tz_member', 'required' => false, 'prompt' => 'Відсутній'])
        ?>
    <?php endif; ?>
    <br>
    <fieldset>
        <legend>Данні про керівника</legend>
        <?= Html::select('Leader[id_pos]', $leader['id_pos'], $positions,
            ['required' => true, 'prompt' => 'Посада...'])
        ?>
        <?= Html::select('Leader[id_sat]', $leader['id_sat'], $statuses,
            ['required' => true, 'prompt' => 'Вчене звання...'])
        ?>
        <?= Html::select('Leader[id_deg]', $leader['id_deg'], $degrees,
            ['required' => true, 'prompt' => 'Науковий ступінь...'])
        ?>
        <?= HtmlHelper::checkbox('Leader[arrival]', 'Відмітка про прибуття на конференцію', $leader['arrival']) ?>
        <label for="leader-email">Електронна скринька:</label>
        <input id="leader-email" type="email" name="Leader[email]" autocomplete="off" value="<?= $leader['email'] ?>"
               placeholder="user@mail.ru">
        <?php
        $phone_number = $leader['phone'] === '' ? 'відсутній' : $leader['phone'];
        echo "<span id=\"phone\">{$phone_number}</span>";
        ?>
        <label for="leader-phone">Телефон:</label>
        <input id="leader-phone" type="tel" pattern="\d{10}" size="10" maxlength="10" name="Leader[phone]"
               value="<?= $leader['phone'] ?>"
               placeholder="Номер телефону">
        <br>

    </fieldset>
    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="hidden" name="action" value="leader_edit">
    <input type="hidden" name="Leader[id]" value="<?= $leader['id'] ?>">
    <input type="hidden" name="id_l" value="<?= $id_l ?>">
</form>
