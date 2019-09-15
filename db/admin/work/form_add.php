<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:16
 */
?>
<!-- Форма добавления работы-->
<header><a href="action.php">Меню</a></header>
<header>Данні роботи</header>
<form class="addworkForm" method="post" action="action.php">
    <?php list_univers("", 1); ?>
    <br>
    <label>Назва роботи:</label><br>
    <textarea name="title" cols="50" rows="4" wrap="virtual" maxlength="255"
              title="Назва роботи (заповнювати на укр.мові)" placeholder="Назва роботи (заповнювати на укр.мові)"
              required></textarea>
    <br>
    <?php list_("sections", "section", "", 1, "Секція..."); ?><br>
    <label>Девіз(ШИФР):</label>
    <input type="text" name="motto" title="Дивіз роботи." placeholder="Девіз..." required autocomplete="off"><br>
    <label>Результати публікації:</label>
    <input type="text" name="public" title="Наприклад: 1 патент, 2 статті"
           placeholder="Наприклад: 1 патент, 2 статті"><br>
    <label>Результати впровадження:</label>
    <input type="text" name="introduction" title="Наприклад: навч.процес,НІП &quot;Дія&quot;"
           placeholder="Наприклад: навч.процес,НИП &quot;Дія&quot;"><br/>
    <fieldset>
        <legend>Службова інформація</legend>
        <label>Тезиси:</label>
        <input type="checkbox" name="tesis" title="Відмітити якщо є тезиси">
        <label>Мертва душа:</label>
        <input type="checkbox" name="dead" title="Відмітити якщо робота фіктивна"><br>
        <textarea name="comments" wrap="virtual" rows="4" cols="80" maxlength="255"
                  placeholder="Зауваження та коментарії"></textarea>
    </fieldset>
    <input type="submit" value="Записати">
    <input type="hidden" name="action" value="work_add">
</form>
