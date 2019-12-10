<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:20
 *
 */

use zukr\base\html\Html;
use zukr\base\html\HtmlHelper;
use zukr\section\SectionHelper;
use zukr\univer\UniverHelper;
use zukr\work\WorkRepository;

$id_w = filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT);

$work = (new WorkRepository())->getById($id_w);
if (empty($work) || !$id_w) {
    Go_page('error');
    exit();
}
$uh = UniverHelper::getInstance();
$univers = $uh->getInvitedDropdownList();
$sh = SectionHelper::getInstance();
$sections = $sh->getDropdownList();
?>
<!-- Редактирование работы -->
<header><a href="action.php">Меню</a></header>
<header>Редагування работы</header>
<form class="editWork" method="post" action="action.php">
    <label for="selunivers">Університет:</label>
    <?= Html::select('Work[id_u]', $work['id_u'], $univers,
        ['id' => 'selunivers', 'required' => true, 'prompt' => 'Оберіть', 'class' => 'w-100'])
    ?>
    <br>
    <label>Назва роботи:</label><br>
    <textarea name="Work[title]" cols="50" rows="4" maxlength="255" id="work-title"
              title="Назва роботи (заповнювати на укр.мові)"
              placeholder="Назва роботи (заповнювати на укр.мові)"
              required class="w-100"><?= htmlspecialchars($work['title']) ?></textarea>
    <br>
    <label for="work-section">Секція:</label>
    <?= Html::select('Work[id_sec]', $work['id_sec'], $sections,
        ['required' => true, 'prompt' => 'Оберіть', 'class' => 'w-100', 'id' => 'work-section'])
    ?>
    <br>
    <label for="work-motto">Девіз(ШИФР):</label>
    <input type="text" name="Work[motto]" id="work-motto" title="Дивіз роботи."
           value="<?= htmlspecialchars($work['motto']) ?>"
           placeholder="Девіз..." required
           autocomplete="off" class="w-100"><br>
    <label for="work-public">Результати публікації:</label>
    <input type="text" name="Work[public]" id='work-public'
           value="<?= htmlspecialchars($work['public']) ?>"
           title="Наприклад: 1 патент, 2 статті"
           placeholder="Наприклад: 1 патент, 2 статті" class="w-100"><br>
    <label for="work-introduction">Результати впровадження:</label>
    <input type="text" name="Work[introduction]" id="work-introduction"
           value="<?= htmlspecialchars($work['introduction']) ?>"
           title="Наприклад: навч.процес,НІП &quot;Дія&quot;"
           placeholder="Наприклад: навч.процес,НИП &quot;Дія&quot;" class="w-100"><br/>
    <fieldset>
        <legend>Службова інформація</legend>
        <label for="work-tesis">Тезиси:</label>
        <?= HtmlHelper::checkbox('Work[tesis]', 'Відмітити якщо є тезиси', $work['tesis'], 'work-tesis') ?>
        <!--<label>Мертва душа:</label>-->
        <?php /*chk_box("dead", "Відмітити якщо робота фіктивна", $wInfo['dead']); */ ?>
        <label>Запросити:</label>
        <?= HtmlHelper::checkbox('Work[invitation]', 'Відмітити для запрошення', $work['invitation'], 'work-invitation') ?>
        <label>Приймає участь у конференції:</label>
        <?= HtmlHelper::checkbox('Work[arrival]', 'Відмітити якщо приймає участь у конференції', $work['arrival'], 'work-arrival') ?>
        <br/>
        <label for="work-comments">Зауваження та коментарії:</label>
        <textarea name="Work[comments]" rows="4" cols="80"
                  maxlength="255" id="work-comments"
                  placeholder="Зауваження та коментарії"
                  class="comments w-100"><?= htmlspecialchars($work['comments']) ?></textarea>
        <label>Сумма балів за рецензію :</label>
        <mark title="Отримана шляхом сумуваня двох рейензій"><?= $work['balls'] ?></mark>
        <br>
    </fieldset>
    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="hidden" name="action" value="work_edit">
    <input type="hidden" name="id_w" value="<?= $work['id'] ?>">
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
        </fieldset>
        <input type="file" name="file" size="20">
        <input type="submit" value="Завантажити">
        <input type="hidden" name="action" value="file_add">
        <input type="hidden" name="id_w" value="<?= $wInfo['id'] ?>">
    </fieldset>
</form>
