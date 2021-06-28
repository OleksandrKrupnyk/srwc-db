<?php

use zukr\base\html\Html;
use zukr\univer\UniverHelper;
use zukr\work\WorkHelper;

$id_w = filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT) ?? false;
$wh = WorkHelper::getInstance();
$uh = UniverHelper::getInstance();
$id_u = null;
if ($id_w) {
    $work = $wh->getWorksRepository()->getById($id_w);
    $id_u = $work['id_u'];
}
$univerIds = $wh->getTakePartUniversIds();
$univers = $uh->getDropDownListShotFull($uh->getTakePartUniversDropDownList($univerIds));
?>
    <!-- Связывание работы -->
    <header><a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a></header>
    <header>Зв'язування роботи</header>
    <form class="linkworkForm form" method="post" action="action.php">
        <?= Html::select('Work[id_u]', $id_u, $univers,
            ['id' => 'selunivers', 'prompt' => 'Оберіть', 'class' => 'w-100', 'size' => 10]) ?>
        <div id="work"></div>
        <table id="table_la" class="w-100">
            <thead>
            <tr>
                <th class="w-50">Керівники</th>
                <th class="w-50">Автори</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td id="leader"></td>
                <td id="autor"></td>
            </tr>
            <tr>
                <td id="leaders"></td>
                <td id="autors"></td>
            </tr>
            </tbody>
        </table>
        <input type="submit" value="Записати">
        <input type="hidden" name="action" value="work_link">
    </form>
    <!-- Окончание Связывание работы -->
<?php
if (isset($id_u) && isset($work)) {
    $JS = <<< SCRIPT
<script type="text/javascript">
$(function(){
    selectWork({$id_u},{$work['id']})
})
</script>
SCRIPT;
    echo $JS;
}