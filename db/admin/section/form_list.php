<?php

use zukr\base\Base;
use zukr\base\html\Html;
use zukr\section\SectionHelper;

Base::$session->setFromParam();
$sh = SectionHelper::getInstance();
?>
<!-- Список секций -->
<header><a href="action.php">Меню</a></header>
<header>
    <div>Список секцій</div>
</header>
<div style="display: flex; flex-flow: row nowrap">
    <?= Html::ol($sh->getDropdownList(), ['id' => 'section-edit-list', 'class' => 'w-50']) ?>
    <!-- Окончание  Список секций  -->
    <form class="form w-50" method="post" action="action.php" name='Section'>
        <label for="section-name">Секція:</label>
        <input id="section-name" name="Section[section]" class="form-input w-100"
               type="text" value="">
        <label for="section-room">Аудиторія:</label><?= Html::select('Section[room]', null, [
            '7-43' => '7-43', '7-53' => '7-53', '7-54' => '7-54'
        ], ['id' => 'section-room', 'class' => 'w-100']) ?>
        <input type="submit" value="Додати" name="save">
        <input type="hidden" name="action" value="section_add">
    </form>
</div>