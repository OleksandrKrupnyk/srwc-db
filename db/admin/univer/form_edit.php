<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:29
 */

use zukr\base\Base;
use zukr\base\html\Html;
use zukr\univer\UniverHelper;

$id_u = filter_input(INPUT_GET, 'id_u', FILTER_VALIDATE_INT);
$uh = UniverHelper::getInstance();
$univer = $uh->getUniverRepository()->getById($id_u);
$uh->getPositionList();
if (empty($univer) || !$id_u) {
    Go_page('error');
}
// redirect_to -> session
Base::$session->setRedirectParam();
?>
<!-- Редактирование данных университета -->
<header><a href="action.php">Меню</a></header>
<header>Редагування данних університету</header>
<form class="editUniver form" method="post" action="action.php">
    <label>Коротка назва</label>
    <input type="text" name="Univer[univer]" title="Скорочена назва. Наприклад:ДДТУ,СумДУ."
           value="<?= $univer['univer'] ?>">
    <br>
    <label>Повна назва</label><br>
    <textarea name="Univer[univerfull]" cols="50" rows="4" maxlength="255" class="w-100"
              title="Повна назва. Наприклад:Кременчуцький національний технічний університет ім М.Остроградського."
              required><?= $univer['univerfull'] ?></textarea>
    <br>
    <label>Назва у у родовому відмінку</label><br>
    <textarea name="Univer[univerrod]" cols="50" rows="4" maxlength="255" class="w-100"
              title="Повна назва університету у родовому відмінку. Наприклад:Кременчуцького національного технічного університету ім М.Остроградського."
              required><?= $univer['univerrod'] ?></textarea>
    <br>
    <label>Поштовий індекс</label>
    <input type="text" name="Univer[zipcode]" size="5" maxlength="5" title="Поштовий індекс.Наприклад: 00103, 51918"
           value="<?= $univer['zipcode'] ?>" pattern="[0-9]{5}" required>
    <br>
    <label>Адреса</label>
    <input type="text" name="Univer[adress]" size="65" maxlength="150"
           title="Вулиця,Місто,Область,Обласний центр.Наприклад: Бериславське шосе, 24, м.Херсон, Запорізька обл."
           value="<?= $univer['adress'] ?>" required>
    Місто<input type="text" name="Univer[town]" size="10" maxlength="30" title="Місто. Наприклад: Дніпро"
                value="<?= $univer['town'] ?>" required>
    <br>
    <label>Посада керівника ВНЗ в родовому відмінку</label>
    <?= Html::select('Univer[posada]', $univer['posada'], $uh->getPositionList(), [
        'title' => 'Посада керівника ВНЗ'
    ]) ?><br>
    <label>Прізвище та ініціали ректора у родовому відмінку</label>
    <input type="text" name="Univer[rector_r]" size="30" maxlength="20"
           title="Наприклад: Коробочці О.В., Васильеву А.В."
           value="<?= $univer['rector_r'] ?>" required>
    <br>
    <label><a href="http://<?= $univer['http'] ?>" target="_blank">Інтернет сторінка</a></label>
    <input type="text" name="Univer[http]" size="60" title="Наприклад: dstu.dp.ua/, sumdu.edu.ua/"
           value="<?= $univer['http'] ?>" required>
    <br>
    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="hidden" name="action" value="univer_edit">
    <input type="hidden" name="id_u" value="<?= $id_u ?>">
</form>
