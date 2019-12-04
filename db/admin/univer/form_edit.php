<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:29
 */?>
<?php
$uInfo = fullinfo("univers", "id", $_GET['id_u']);
?>
<!-- Редактирование данных университета -->
<header><a href="action.php">Меню</a></header>
<header>Редагування данних університету</header>
<form class="editUniver" method="post" action="action.php">
    <label>Коротка назва</label>
    <input type="text" name="univer" title="Скорочена назва. Наприклад:ДДТУ,СумДУ." value="<?= $uInfo['univer'] ?>">
    <br>
    <label>Повна назва</label><br>
    <textarea name="univerfull" cols="50" rows="4" wrap="virtual" maxlength="255"
              title="Повна назва. Наприклад:Кременчуцький національний технічний університет ім М.Остроградського."
              required><?= $uInfo['univerfull'] ?></textarea>
    <br>
    <label>Назва у у родовому відмінку</label><br>
    <textarea name="univerrod" cols="50" rows="4" wrap="virtual" maxlength="255"
              title="Повна назва університету у родовому відмінку. Наприклад:Кременчуцького національного технічного університету ім М.Остроградського."
              required><?= $uInfo['univerrod'] ?></textarea>
    <br>
    <label>Поштовий індекс</label>
    <input type="text" name="zipcode" size="5" maxlength="5" title="Поштовий індекс.Наприклад: 00103, 51918"
           value="<?= $uInfo['zipcode'] ?>" pattern="[0-9]{5}" required>
    <br>
    <label>Адресса</label>
    <input type="text" name="adress" size="65" maxlength="150"
           title="Вулиця,Місто,Область,Обласний центр.Наприклад: Бериславське шосе, 24, м.Херсон, Запорізька обл."
           value="<?= $uInfo['adress'] ?>" required>
    Місто<input type="text" name="town" size="10" maxlength="30" title="Місто. Наприклад: Дніпро"
                value="<?= $uInfo['town'] ?>" required>
    <br>
    <label>Посада керівника ВНЗ в родовому відмінку</label>
    <?php select_positionVNZ($uInfo['posada']) ?><br>
    <label>Прізвище та ініціали ректора у родовому відмінку</label>
    <input type="text" name="rector_r" size="30" maxlength="20" title="Наприклад: Коробочці О.В., Васильеву А.В."
           value="<?= $uInfo['rector_r'] ?>" required>
    <br>
    <label><a href="http://<?= $uInfo['http'] ?>" target="_blank">Інтернет сторінка</a></label>
    <input type="text" name="http" size="60" title="Наприклад: dstu.dp.ua/, sumdu.edu.ua/"
           value="<?= $uInfo['http'] ?>" required>
    <br>
    <input type="submit" value="Змінити">
    <input type="hidden" name="action" value="univer_edit">
    <input type="hidden" name="id" value="<?= $_GET['id_u'] ?>">
    <input type="hidden" name="from" value="<?= $_GET['FROM'] ?>">
</form>
