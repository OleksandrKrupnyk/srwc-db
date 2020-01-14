<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:29
 */

use zukr\base\Base;

$settings = Base::$param;
$str = ($settings->ALLOW_EMAIL === "1")
    ? 'УВАГА! Налаштування розсилки ДОЗВОЛЕНО'
    : "Безпечна робота розсилка вымкнута!";
$buttonName = ("1" == $settings->ALLOW_EMAIL)
    ? "Надіслати листи"
    : "Перевірити листи";
?>
<!-- Список на отправку писем-->
<!-- Меню действий -->
<header><a href="action.php">Меню</a></header>
<header>Список розсилки</header>
<h1 title="Попередження"><?= $str ?></h1>
<form method="post" action="sentmails.php">
    <fieldset><?= list_emails("leaders"); ?></fieldset>
    <label>Перегляд листа</label>
    <div id="previewletter2leaders"><?= file_get_contents("letter2leaders.tte"); ?></div>
    <label>Редагування листа</label>
    <fieldset>
        <mark>{whom}</mark>
        --Кому Іванов Іван Іванович;
        <mark>{title}</mark>
        --Назва роботи;<br>
        <mark>{link}</mark>
        --посылання на підтвердження отримання листа.
    </fieldset>
    <textarea name="letter2leaders" id="letter2leaders"
              class="w-100"><?= file_get_contents("letter2leaders.tte"); ?></textarea>
    <input type="submit" value="<?= $buttonName ?>">
    <input type="hidden" name="t" value="l">
</form>
<form method="post" action="sentmails.php">
    <h2>Листи для авторів</h2>
    <fieldset><?= list_emails("autors") ?></fieldset>
    <label>Перегляд листа</label>
    <div id="previewletter2autors"><?= file_get_contents("letter2autors.tte"); ?></div>
    <label>Редагування листа</label>
    <fieldset>
        <mark>{whom}</mark>
        --Кому Іванов Іван Іванович;
        <mark>{title}</mark>
        --Назва роботи;<br>
        <mark>{link}</mark>
        --посылання на підтвердження отримання листа.
    </fieldset>
    <textarea name="letter2autors" id="letter2autors" class="w-100"><?= file_get_contents("letter2autors.tte"); ?>
        </textarea>
    <input type="submit" value="<?= $buttonName ?>">
    <input type="hidden" name="t" value="a">
</form>
<script>

    //Изменение в Области textarea
    const textAC1 = $('#letter2autors'),
        textAC2 = $('#letter2leaders');
    textAC1.on('keyup', function () {
        $('#previewletter2autors').html(textAC1.val());
    })
        .on('change', function () {
            $('#previewletter2autors').html(textAC1.val());
            //console.log("Изменеие зафиксированы");
        });
    textAC2.on('keyup', function () {
        $('#previewletter2leaders').html(textAC2.val());
    })
        .on('change', function () {
            $('#previewletter2leaders').html(textAC2.val());
            //console.log("Изменеие зафиксированы");
        });
</script>