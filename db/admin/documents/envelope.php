<?php

use zukr\univer\UniverHelper;

$univers = UniverHelper::getInstance()->getInvited();
$envelops = '';
foreach ($univers as $u) {
    $envelop = <<<__ENVELOP__
    <div id="fromAdress">
        <strong><ins>Всеукраїнський конкурс студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</ins></strong><br>
        <em>вул.&nbsp;Дніпробудівська,2 м.&nbsp;Кам’янське,
        <br>Дніпропетровська обл.</em><br>
        <strong>51918</strong>
    </div>
    <div id="whomAdress">
        <strong><ins>{$u['univerfull']}</ins></strong><br>
        <em>{$u['adress']}</em><br>
        <strong>{$u['zipcode']}</strong>
    </div>
    <hr>
__ENVELOP__;
    $envelops .= $envelop;
}
$html = '<div class="envelope">' . $envelops . '</div>';
echo $html;