<?php

use zukr\base\Base;
use zukr\leader\LeaderHelper;

Base::$session->setFromParam();
$lh = LeaderHelper::getInstance();

?>
<!--Формування списку запрошень журі-->
<header><a href="action.php">Меню</a></header>
<header>Запрошення (<?= $lh->getCountInvitationLeaders() ?> всього)</header>
<menu class="viewTableMenu">
    <li><a href="lists.php?list=invitation_1">Листи ректорам</a></li>
</menu>
<table id="tableInvitationLeaders">
    <tr>
        <th class="w-50">ВНЗ що надіслали роботи</th>
        <th class="w-50">Представники / Керівники</th>
    </tr>
    <tr>
        <td class="w-50"><?= list_univers('', 10, true, true, true) ?></td>
        <td class="w-50">
            <div id="listleaders"></div>
        </td>
    </tr>
</table>
