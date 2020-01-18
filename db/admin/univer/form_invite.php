<?php

use zukr\base\Base;
use zukr\univer\UniverHelper;
use zukr\univer\UniverRepository;

$univerRepository = new UniverRepository();
$univers = $univerRepository->getAllUniversAsArrayFromDB();
$uh = UniverHelper::getInstance();
if (!Base::$user->getUser()->isAdmin()) {
    Base::$session->setFlash('recordSaveMsg', 'Заборонена дія');
    Base::$session->setFlash('recordSaveType', 'warn');
    Go_page(null);
}
$session = Base::$session;
$session->setFromParam();
?>
<!-- Редактирование списка университетов в которые следует направить первое информационное сообщение -->
<header><a href='action.php'>Меню</a></header>
<header>Список университетів</header>
<table id='tableInviteUnivers' class="zebra">
    <tr>
        <th>№</th>
        <th>Університет</th>
        <th>?</th>
    </tr>
    <?php
    $i = 1;
    foreach ($univers as $key => $univer):
        echo '<tr data-key="' . $key . '"><td>' . $i++ . '</td>
              <td><a href="action.php?action=univer_edit&id_u=' . $key . '">' . $univer['univerfull'] . '</a></td>
              <td>' . \zukr\base\html\HtmlHelper::checkboxStyled('invitation', '', $univer['invite']) . '</td>
              </tr>';

    endforeach; ?>
</table><a href='lists.php?action=adress'><input type='button' value='Друкувати список'></a>
<?= $uh->registerJS() ?>
