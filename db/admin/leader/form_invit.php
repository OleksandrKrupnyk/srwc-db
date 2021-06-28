<?php

use zukr\base\Base;
use zukr\base\html\Html;
use zukr\leader\LeaderHelper;
use zukr\univer\UniverHelper;

Base::$session->setFromParam();
$lh = LeaderHelper::getInstance();
$uh = UniverHelper::getInstance();
$univers = $uh->getDropDownListShotFull(
    $uh->getTakePartUniversDropDownList(
        $uh->getUniversIdWhoSendWork()
    )
);
?>
    <!--Формування списку запрошень журі-->
    <header><a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a></header>
    <header style="justify-content:center">Запрошення (&nbsp;<span
                id="countLeaders"><?= $lh->getCountInvitationLeaders() ?></span>&nbsp;всього)
    </header>
    <a class="btn" href="lists.php?action=invitation">Листи ректорам</a>
    <table id="tableInvitationLeaders" class="w-100">
        <tr>
            <th class="w-50">ВНЗ що надіслали роботи</th>
            <th class="w-50">Представники / Керівники</th>
        </tr>
        <tr>
            <td class="w-50" style="vertical-align: top"><?= Html::select('id_u', null, $univers, [
                    'size' => 20,
                    'class' => 'w-100',
                    'id' => 'shortlistunivers'
                ]) ?></td>
            <td class="w-50" style="vertical-align:top;">
                <div id="listleaders">
                    <div style="padding-top:20% ">
                        <i class="icofont-2x icofont-hand-left"></i> Оберіть вищий навчальний заклад
                    </div>
                </div>
            </td>
        </tr>
    </table>
<?= $lh->registerJS('leader.js') ?>