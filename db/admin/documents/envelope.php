<?php

use zukr\univer\UniverHelper;

$pdf = \zukr\pdf\PdfWrapper::getInstance();

$univers = UniverHelper::getInstance()->getInvited();
$envelops = '';
foreach ($univers as $u) {
    $envelop = <<<__ENVELOP__
    <div class="from-address">
        <strong><ins>Всеукраїнський конкурс студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</ins></strong><br>
        <em>вул.&nbsp;Дніпробудівська,2 м.&nbsp;Кам’янське,
        <br>Дніпропетровська обл.</em><br>
        <strong>51918</strong>
    </div>
    <div class="whom-address">
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
