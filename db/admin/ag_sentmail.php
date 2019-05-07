<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:29
 */
?>
<!-- Меню действий -->
    <header><a href="action.php">Меню</a></header>
    <header>Список розсилки</header>
    <?php  $str=($settings['ALLOW_EMAIL'] == "1")?"УВАГА! Налаштування розсилки ДОЗВОЛЕНО":"Безпечна робота розсилка вымкнута!";
           $buttonName = ("1" == $settings['ALLOW_EMAIL'])?"Надіслати листи":"Перевірити листи";
    echo "<h1 title=\"Попередження\">{$str}</h1>"; ?>
    <form method="post" action="sentmails.php">
        <fieldset>
        <?php
        list_emails("leaders");
        ?>
        </fieldset>
        <label>Перегляд листа</label>
        <div id="previewletter2leaders"><?php echo file_get_contents("letter2leaders.tte");?></div>
        <label>Редагування листа</label>
        <fieldset>
            <mark>{whom}</mark>--Кому Іванов Іван Іванович; <mark>{title}</mark>--Назва роботи;<br>
            <mark>{link}</mark>--посылання на підтвердження отримання листа.
        </fieldset>
        <textarea name="letter2leaders" id="letter2leaders"><?php echo file_get_contents("letter2leaders.tte");?>
        </textarea>
        <input type="submit" value="<?=$buttonName?>">
        <input type="hidden" name="t" value="l">
    </form>
    <form method="post" action="sentmails.php">
        <h2>Листи для авторів</h2>
        <fieldset>
        <?php
        list_emails("autors");
        ?>
        </fieldset>
        <label>Перегляд листа</label>
        <div id="previewletter2autors"><?php echo file_get_contents("letter2autors.tte");?></div>
        <label>Редагування листа</label>
        <fieldset>
        <mark>{whom}</mark>--Кому Іванов Іван Іванович; <mark>{title}</mark>--Назва роботи;<br>
            <mark>{link}</mark>--посылання на підтвердження отримання листа.
        </fieldset>
        <textarea name="letter2autors" id="letter2autors"><?php echo file_get_contents("letter2autors.tte");?>
        </textarea>
        <input type="submit" value="<?=$buttonName?>">
        <input type="hidden" name="t" value="a">
    </form>
