<?php

use zukr\base\html\Html;
use zukr\base\html\HtmlHelper;
use zukr\degree\DegreeRepository;
use zukr\position\PositionRepository;
use zukr\section\SectionHelper;
use zukr\status\StatusRepository;
use zukr\univer\UniverHelper;
use zukr\work\WorkHelper;

$uh = UniverHelper::getInstance();
$wh = WorkHelper::getInstance();
$univerIds = $wh->getTakePartUniversIds();
//$univers = $uh->getDropDownListShotFull($uh->getTakePartUniversDropDownList($univerIds));
$univers = $uh->getInvitedDropdownList();

$positions = (new PositionRepository())->getDropDownList();
$statuses = (new StatusRepository())->getDropDownList();
$degrees = (new DegreeRepository())->getDropDownList();
$sh = SectionHelper::getInstance();
$sections = $sh->getDropdownList();
$id_u = filter_input(INPUT_GET, 'id_u', FILTER_VALIDATE_INT);
?>
    <!-- Форма добавления всех сведений-->
    <header><a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a></header>
    <header>Усі данні роботи</header>
    <form class="addAllForm form" method="post" action="action.php">
        <label>Університет</label>
        <?= Html::select('Work[id_u]', $id_u, $univers,
            ['id' => 'select-univer', 'required' => true, 'prompt' => 'Оберіть', 'class' => 'w-100'])
        ?>
        <br>
        <div class="listsaotursleaders">
            <div class="col-select">
                <label for="select-authors">Автори</label>
                <select id="select-authors" size="10" name="id_a">
                    <option value="-1" disabled>Оберіть університет</option>
                </select>
            </div>
            <div class="col-select">
                <label for="select-leaders">Керівники</label>
                <select id="select-leaders" size="10" name="id_l">
                    <option value="-1" disabled>Оберіть університет</option>
                </select>
            </div>
        </div>
        <br>
        <div style="display: flex; flex-flow: row nowrap;justify-content: space-between">
            <fieldset id="data-author" style="width: 48%;">
                <legend>Данні автора</legend>
                <div class="form-input">
                    <label for="author-suname">Прізвище</label>
                    <input type="text" id="author-suname" name="Author[suname]" title="Прізвище" placeholder="Прізвище"
                           required>
                </div>
                <div class="form-input">
                    <label for="author-name">Ім'я</label>
                    <input type="text" id="author-name" name="Author[name]" title="Ім'я" placeholder="Ім'я"
                           required>
                </div>
                <div class="form-input">
                    <label for="author-lname">По-батькові</label>
                    <input type="text" id="author-lname" name="Author[lname]" title="По-батькові"
                           placeholder="По-батькові"><br>
                </div>
                <div class="form-input">
                    <label for="author-curse">Курс навчання</label>
                    <?= HtmlHelper::course(['name' => 'Author[curse]']) ?><br>
                </div>
                <div class="form-input">
                    <label>Електронна скринька:</label>
                    <input type="email" name="Author[email]" title="Наприклад:user@mail.ru"
                           placeholder="Електронна скринька"></div>
                <div class="form-input">
                    <label>Телефон:</label>
                    <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="Author[phone]"
                           title="Наприклад:0985622012"
                           placeholder="Номер телефону"></div>
                <input type="hidden" name="Author[id_u]" value="0" id="author-id_u">
            </fieldset>
            <br>
            <fieldset id="data-leader" style="width: 48%;">
                <legend>Данні про керівника</legend>
                <div class="form-input">
                    <label for="leader-suname">Прізвище</label>
                    <input type="text" name="Leader[suname]" title="Прізвище" placeholder="Прізвище" required>
                </div>
                <div class="form-input">
                    <label for="leader-name">Ім'я</label>
                    <input type="text" name="Leader[name]" title="Ім'я" placeholder="Ім'я" id="name" required>
                </div>
                <div class="form-input">
                    <label for="leader-lname">По-батькові</label>
                    <input type="text" name="Leader[lname]" title="По-батькові" placeholder="По-батькові" id="lname"
                           required></div>
                <div class="form-input">
                    <label for="leader-position">Посада</label>
                    <?= Html::select('Leader[id_pos]', null, $positions,
                        ['required' => true, 'prompt' => 'Посада...', 'id' => 'leader-position'])
                    ?></div>
                <div class="form-input">
                    <label for="leader-status">Вчене звання</label>
                    <?= Html::select('Leader[id_sat]', 1, $statuses,
                        ['required' => true, 'prompt' => 'Вчене звання...', 'id' => 'leader-status'])
                    ?></div>
                <div class="form-input">
                    <label for="leader-degree">Науковий ступінь</label>
                    <?= Html::select('Leader[id_deg]', 1, $degrees,
                        ['required' => true, 'prompt' => 'Науковий ступінь...', 'id' => 'leader-degree'])
                    ?></div>
                <div class="form-input">
                    <label>Електронна скринька</label>
                    <input type="email" name="Leader[email]" title="Наприклад:user@mail.ru"
                           placeholder="Електронна скринька">
                </div>
                <div class="form-input">
                    <label>Телефон</label>
                    <input type="tel" pattern="\d{10}" size="10" maxlength="10" name="Leader[phone]"
                           title="Наприклад:0985622012"
                           placeholder="Номер телефону">
                </div>
                <input type="hidden" name="Leader[id_u]" value="0" id="leader-id_u">
            </fieldset>
        </div>

        <fieldset>
            <legend>Данні роботи</legend>
            <label>Назва роботи:</label><br>
            <textarea name="Work[title]" cols="50" rows="4" maxlength="255" id="work-title"
                      title="Назва роботи (заповнювати на укр.мові)"
                      placeholder="Назва роботи (заповнювати на укр.мові)"
                      required class="w-100"></textarea>
            <div class="form-input">
                <label for="work-section">Секція:</label>
                <?= Html::select('Work[id_sec]', null, $sections,
                    ['required' => true, 'prompt' => 'Оберіть', 'class' => 'w-100', 'style' => 'max-width:100%!important', 'id' => 'work-section'])
                ?></div>
            <div class="form-input">
                <label for="work-motto">Девіз(ШИФР):</label>
                <input type="text" name="Work[motto]" id="work-motto" title="Девіз роботи." placeholder="Девіз..."
                       required
                       autocomplete="off" class="w-100"><br>
            </div>
            <div class="form-input">
                <label for="work-public">Результати публікації:</label>
                <input type="text" name="Work[public]" id='work-public' title="Наприклад: 1 патент, 2 статті"
                       placeholder="Наприклад: 1 патент, 2 статті" class="w-100"><br>
            </div>
            <div class="form-input">
                <label for="work-introduction">Результати впровадження:</label>
                <input type="text" name="Work[introduction]" id="work-introduction"
                       title="Наприклад: навч.процес,НІП &quot;Дія&quot;"
                       placeholder="Наприклад: навч.процес,НИП &quot;Дія&quot;" class="w-100"><br/>
            </div>
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
        </fieldset>
        <br>
        <input type="submit" value="Зберегти та вийти" name="save+exit">
        <input type="hidden" name="action" value="all_add">
    </form>
    <!-- Окончание Форма добавления всех сведений-->
<?= $wh->registerJS('work.js') ?>