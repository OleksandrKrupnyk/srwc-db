<?php

use zukr\base\Base;
use zukr\base\html\Html;
use zukr\section\SectionHelper;

Base::$session->setFromParam();
$sh = SectionHelper::getInstance();
$list = [];
foreach ($sh->getSections() as $id => $item) {
    $list[$id] = Html::tag('span', $item['section'] ?? '', ['class' => 'editable']) . '<br/>'
        . Html::a($item['link'] ?? '', $item['link'] ?? '#', ['class' => 'editable-connect-meet'])
        . Html::a('', '#', ['class' => 'js-delete-list-item']);
}
?>
    <!-- Список секций -->
    <header><a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a></header>
    <header>
        <div>Список секцій</div>
    </header>
    <header style="width:fit-content;">
        <a href="action.php?action=rooms_edit">Аудиторії</a>
    </header>
    <div style="display: flex; flex-flow: row nowrap">
        <?= Html::ol($list, ['id' => 'section-edit-list', 'class' => 'w-50 pointer', 'title' => 'Клацніть для редагування']) ?>
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
    <script src="../js/jquery.jeditable.min.js"></script>
<?php echo $sh->registerJS('section.js');