<?php

use zukr\base\Base;

Base::$session->setFromParam();
?>
<!--Формування списку запрошень журі-->
<header><a href="action.php">Меню</a></header>
<header>Запрошення (<?= $count ?> всього)</header>
<menu class="viewTableMenu">
    <li><a href="lists.php?list=invitation_1">Листи ректорам</a></li>
</menu>
<table id="tableInvitationLeaders">
    <tr>
        <th>ВНЗ що надіслали роботи</th>
        <th>Представники / Керівники</th>
    </tr>
    <tr>
        <td><?= list_univers('', 10, true, true, true) ?></td>
        <td>
            <div id="listleaders"></div>
        </td>
    </tr>
</table>
