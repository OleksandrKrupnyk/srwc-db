<?php

use zukr\base\Base;
use zukr\base\html\Html;
use zukr\scanfiles\ScanFilesRepository;
use zukr\univer\UniverHelper;

Base::$session->setFromParam();
$scfr = new ScanFilesRepository();
$scanFiles = $scfr->getAllScannedFiles();
$uh = UniverHelper::getInstance();
$univers = $uh->getInvitedDropdownList();
$listFiles = [];
foreach ($scanFiles as $scanFile) {
    $content = 'Для ' . $scanFile['univerrod'] . '&#9;' . Html::a($scanFile['filename'], $scanFile['file'], [
            'class' => 'link-file js-window-iframe', 'data-file' => $scanFile['filename'],
        ]) . '&nbsp;' . Html::a('', 'action.php?action=invitation_delete&id=' . $scanFile['id'], [
            'title' => 'Видалити файл',
            'class' => 'link-delete-file'
        ]);
    $listFiles[] = $content;
}
?>
<header><a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a></header>
<h1>Список відсканованих запрошень</h1>
<?php if (!empty($listFiles)): ?>
    <form class="form">
        <?= Html::ol($listFiles) ?>
    </form>
<?php else: ?>
    <div class="form">
        <mark>Нема файлів для відображення</mark>
    </div>
<?php endif; ?>

<form class="addScanFiles form" enctype="multipart/form-data" method="post" action="action.php?action=invitation_add">
    <fieldset>
        <legend>Завантаження сканованих запрошень</legend>
        <label for="selunivers">ВНЗ:</label><br/>
        <?= Html::select('ScanFiles[id_u]', null, $univers,
            ['id' => 'selunivers', 'required' => true, 'prompt' => 'ВНЗ...', 'class' => 'w-100', 'style' => 'max-width:100%!important'])
        ?><br/>
        <label>Файл:</label>
        <input type="file" name="file" size="20"><br/>
        <input type="submit" value="Завантажити">
        <a href="action.php" class="btn" >Вийти</a>
        <input type="hidden" name="action" value="invitation_add">
    </fieldset>
</form>