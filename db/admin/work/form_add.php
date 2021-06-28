<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 14.02.2017
 * Time: 20:16
 */


use zukr\base\Base;
use zukr\base\html\Html;
use zukr\base\html\HtmlHelper;
use zukr\section\SectionHelper;
use zukr\univer\UniverHelper;

$id_u = filter_input(INPUT_GET, 'id_u', FILTER_VALIDATE_INT);

$uh = UniverHelper::getInstance();
$univers = $uh->getInvitedDropdownList();
$sh = SectionHelper::getInstance();
$sections = $sh->getDropdownList();
// redirect_to -> session
Base::$session->setRedirectParam();
?>
<!-- Форма добавления работы-->
<header><a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a></header>
<header>Данні роботи</header>
<form class="addworkForm form" method="post" action="action.php">
    <label for="selunivers">Університет:</label>
    <?= Html::select('Work[id_u]', $id_u, $univers,
        ['id' => 'selunivers', 'required' => true, 'prompt' => 'Оберіть', 'class' => 'w-100'])
    ?>
    <br>
    <label>Назва роботи:</label><br>
    <textarea name="Work[title]" cols="50" rows="4" maxlength="255" id="work-title"
              title="Назва роботи (заповнювати на укр.мові)"
              placeholder="Назва роботи (заповнювати на укр.мові)"
              required class="w-100"></textarea>
    <br>
    <label for="work-section">Секція:</label>
    <?= Html::select('Work[id_sec]', null, $sections,
        ['required' => true, 'prompt' => 'Оберіть', 'class' => 'w-100', 'id' => 'work-section'])
    ?>

    <label for="work-motto">Девіз(ШИФР):</label>
    <input type="text" name="Work[motto]" id="work-motto" title="Девіз роботи." placeholder="Девіз..." required
           autocomplete="off" class="w-100"><br>
    <label for="work-public">Результати публікації:</label>
    <input type="text" name="Work[public]" id='work-public' title="Наприклад: 1 патент, 2 статті"
           placeholder="Наприклад: 1 патент, 2 статті" class="w-100"><br>
    <label for="work-introduction">Результати впровадження:</label>
    <input type="text" name="Work[introduction]" id="work-introduction"
           title="Наприклад: навч.процес,НІП &quot;Дія&quot;"
           placeholder="Наприклад: навч.процес,НИП &quot;Дія&quot;" class="w-100"><br/>
    <fieldset>
        <legend>Службова інформація</legend>
        <label for="work-tesis">Тезиси:</label>
        <?= HtmlHelper::checkbox('Work[tesis]', 'Відмітити якщо є тезиси', 0, 'work-tesis') ?>
        <label for="work-dead">Мертва душа:</label>
        <?= HtmlHelper::checkbox('Work[dead]', 'Відмітити якщо робота фіктивна', 0, 'work-dead') ?><br/>
        <label for="work-comments">Зауваження та коментарії:</label>
        <textarea name="Work[comments]" rows="4" cols="80" maxlength="255" id="work-comments"
                  placeholder="Зауваження та коментарії" class="comments w-100"></textarea>
    </fieldset>
    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="hidden" name="action" value="work_add">
</form>
