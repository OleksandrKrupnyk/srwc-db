<?php

use zukr\base\html\Html;
use zukr\univer\UniverHelper;
use zukr\work\WorkHelper;
use zukr\work\WorkRepository;

$id_w = filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT);
if ($id_w) {
    $work = (new WorkRepository())->getById($id_w);
    $id_u = $work['id_u'];
}
$id_u = $id_u ?? null;
$uh = UniverHelper::getInstance();
$wh = WorkHelper::getInstance();
$univerIds = $wh->getTakePartUniversIds();
$univers = $uh->getDropDownListShotFull($uh->getTakePartUniversDropDownList($univerIds));
?>
    <!-- Связывание работы -->
    <header><a href="action.php">Меню</a></header>
    <header>Зв'язування роботи</header>
    <form class="linkworkForm" method="post" action="action.php">
        <?php echo Html::select('Work[id_u]', $id_u, $univers,
            ['id' => 'selunivers', 'prompt' => 'Оберіть', 'class' => 'w-100', 'size' => 10]) ?>
        <div id="work"></div>
        <table id="table_la" class="w-100">
            <tr>
                <th>Керівники</th>
                <th>Автори</th>
            </tr>
            <tr>
                <td id="leader"></td>
                <td id="autor"></td>
            </tr>
            <tr>
                <td id="leaders"></td>
                <td id="autors"></td>
            </tr>
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
    selectWork({$id_u},{$work['id']});
    $(".select-link-author").chosen({disable_search_threshold: 10});
})
</script>
SCRIPT;
    echo $JS;
}