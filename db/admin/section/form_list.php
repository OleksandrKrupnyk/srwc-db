<?php

use zukr\base\Base;
use zukr\base\html\Html;
use zukr\section\SectionHelper;

Base::$session->setFromParam();
$sh = SectionHelper::getInstance();
$list = [];
foreach ($sh->getDropdownList() as $id => $item) {
    $list[$id] = Html::tag('span', $item, ['class' => 'editable']) . Html::a('', '#', ['class' => 'js-delete-list-item']);

}
?>
<!-- Список секций -->
<header><a href="action.php">Меню</a></header>
<header>
    <div>Список секцій</div>
</header>
<header>
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
<script>
    $(document).ready(function () {
            let isLoading = false;
            $('.editable').editable(function (value, settings) {
                let id = parseInt($(this).parent('li').data('key') || 0);
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"section": value, "action": "change-section", "id_sec": id},
                    cache: false,
                    success: function (response) {
                        try {
                            const data = JSON.parse(response);
                            $.notify(data.message || 'No message', data.type || 'error');
                        } catch (e) {
                            console.log(e);
                        }
                    },
                    error: function (e) {
                        console.log(e)
                    }
                });
                return (value);
            }, {
                submit: 'Зберегти',
                cancel: 'Відміна',
                submitcssclass: 'btn',
                cancelcssclass: "btn",
                tooltip: "Клацніть для редагування",
                size: "75"
            });
            $('.js-delete-list-item').on('click', function (e) {
                e.preventDefault();
                let id = parseInt($(this).parent('li').data('key') || 0);
                $(this).parent('li').remove();
                console.log(id);
            })
        }
    );
</script>