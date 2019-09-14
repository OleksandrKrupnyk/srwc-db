<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:20
 */?>
<?php $wInfo = fullinfo("works", "id", $_GET['id_w']); ?>
<header><a href="action.php">Меню</a></header>
<header>Редагування работы</header>
<form class="editWork" method="post" action="action.php">
    <?php list_univers($wInfo['id_u'], 1); ?>
    <br>
    <label>Назва роботи:</label><br>
    <textarea name="title" cols="50" rows="4" wrap="virtual" maxlength="255"
              title="Назва роботи (заповнювати на укр.мові)" required>
<?php echo(htmlspecialchars($wInfo['title'])); ?>
</textarea>
    <br>
    <?php list_("sections", "section", $wInfo['id_sec'], 1, "Секція..."); ?><br>
    <label>Девіз(ШИФР):</label>
    <input type="text" name="motto" title="Дивіз роботи." value="<?php echo(htmlspecialchars($wInfo['motto'])); ?>"
           required autocomplete="off">
    <br>
    <label>Результати публікації:</label><input type="text" name="public" title="Наприклад: 1 патент, 2 статті"
                                                value="<?= $wInfo['public'] ?>"><br>
    <label>Результати впровадження:</label><input type="text" name="introduction"
                                                  title="Наприклад: навч.процес,НИП &quot;Дія&quot; "
                                                  value="<?= $wInfo['introduction'] ?>"><br/>
    <fieldset>
        <legend>Службова інформація</legend>
        <label>Тезиси:</label>
        <?php chk_box("tesis", "Відмітити якщо є тезиси", $wInfo['tesis']); ?>
        <!--<label>Мертва душа:</label>-->
        <?php /*chk_box("dead", "Відмітити якщо робота фіктивна", $wInfo['dead']); */?>
        <label>Запросити:</label>
        <?php chk_box("invitation", "Відмітити для запрошення", $wInfo['invitation']); ?>
        <label>Приймає участь у конференції:</label>
        <?php chk_box("arrival", "Відмітити якщо приймає участь у конференції", $wInfo['arrival']); ?><br>
        <textarea name="comments" wrap="virtual" rows="4" cols="80" maxlength="255"
                  placeholder="Зауваження та коментарії">
<?php echo(htmlspecialchars($wInfo['comments'])); ?>
</textarea>
	<label>Сумма балів за рецензію :</label><mark title="Отримана шляхом сумуваня двох рейензій"><?= $wInfo['balls']?></mark>

    <br>
    </fieldset>
    <input type="submit" value="Змінити">
    <input type="hidden" name="action" value="work_edit">
    <input type="hidden" name="id_w" value="<?= $wInfo['id'] ?>">
</form>
<!-- Форма загрузкки файлов работы при её редактировании -->
<form class="addWorkFiles" enctype="multipart/form-data" method="post" action="action.php">
    <fieldset>
        <legend>Завантаження</legend>
        <?php echo(list_files($_GET['id_w'])); ?>
        <fieldset>
            <legend>Оберіть тип файлу:</legend>
            <label>Текст з роботою</label><input type="radio" name="typeoffile" value="common" checked/>
            <label>Інші файли</label><input type="radio" name="typeoffile" value="information"/>
            <label>Тези</label><input type="radio" name="typeoffile" value="tesis"/>
            <label>Презентація</label><input type="radio" name="typeoffile" value="presentation"/>


            <!-- <input type="checkbox" name="presentation" title="Відмітити якщо це файл презентації">-->
        </fieldset>
        <input type="file" name="file" size="20">
        <input type="submit" value="Завантажити">
        <!--<input type="checkbox" name="presentation" title="Відмітити якщо це файл презентації">-->
        <input type="hidden" name="action" value="file_add">
        <input type="hidden" name="id_w" value="<?= $wInfo['id'] ?>">
    </fieldset>
</form>
