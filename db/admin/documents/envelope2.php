<?php

use zukr\base\Base;

$db = Base::$app->db;
$univers = $db->rawQuery("
SELECT u.univerfull,u.adress,u.zipcode FROM univers AS u WHERE u.id IN (SELECT DISTINCT id_u 
FROM works AS w 
WHERE w.invitation = 1 AND id<>1) ORDER BY u.univerfull;
");
$envelops = '';
if (!empty($univers)) {
    foreach ($univers as $row) {
        $envelop = <<<__ENVELOP__
    <div class="from-address">
        <strong><ins>Всеукраїнський конкурс студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</ins></strong><br>
        <em>вул.&nbsp;Дніпробудівська,2 м.&nbsp;Кам’янське,
        <br>Дніпропетровська обл.</em><br><strong>51918</strong>
    </div>
    <div class="whom-address">
        <strong><ins>{$row['univerfull']}</ins></strong><br>
        <em>{$row['adress']}</em><br>
        <strong>{$row['zipcode']}</strong>
    </div>
    <hr>
__ENVELOP__;
        $envelops .= $envelop;
    }
}
$html = '<div class="envelope">' . $envelops . '</div>';
echo $html;
