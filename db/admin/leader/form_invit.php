<?php

use zukr\base\Base;
use zukr\base\html\Html;
use zukr\leader\LeaderHelper;
use zukr\univer\UniverHelper;

Base::$session->setFromParam();
$lh = LeaderHelper::getInstance();
$uh = UniverHelper::getInstance();
$univers = $uh->getDropDownListShot(
    $uh->getTakePartUniversDropDownList(
        $uh->getUniversIdWhoSendWork()
    )
);
?>
    <!--Формування списку запрошень журі-->
    <header><a href="action.php">Меню</a></header>
    <header style="justify-content:center">Запрошення (&nbsp;<span
                id="countLeaders"><?= $lh->getCountInvitationLeaders() ?></span>&nbsp;всього)
    </header>
    <menu class="viewTableMenu">
        <li><a href="lists.php?list=invitation_1">Листи ректорам</a></li>
    </menu>
    <table id="tableInvitationLeaders">
        <tr>
            <th class="w-50">ВНЗ що надіслали роботи</th>
            <th class="w-50">Представники / Керівники</th>
        </tr>
        <tr>
            <td class="w-50"><?= Html::select('id_u', null, $univers, [
                    'size' => 10,
                    'class' => 'w-100',
                    'id' => 'shortlistunivers'
                ]) ?></td>
            <td class="w-50">
                <div id="listleaders"></div>
            </td>
        </tr>
    </table>
<?= $lh->registerJS() ?>