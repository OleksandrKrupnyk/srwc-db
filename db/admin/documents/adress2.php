<?php

use zukr\base\Base;
use zukr\pdf\PdfWrapper;

$db = Base::$app->db;
$pdf = PdfWrapper::getInstance();
$univers = $db->rawQuery("
SELECT u.univerfull,u.adress FROM univers AS u WHERE u.id IN (SELECT DISTINCT id_u 
FROM works AS w 
WHERE w.invitation = 1 AND id<>1) ORDER BY u.univerfull;
");
//Только те работы у которых есть приглашенные работы минус ДДТУ
$html = '<div class="adress2">'
    . '<header>Список розсилки 2-го інформаційного повідомлення <br>Всеукраїнського конкурсу студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</header>'
    . '<div id="table">
<table>
    <thead>
        <tr><th>№</th><th>Кому</th><th>Адреса</th></tr>
    </thead>
    <tbody>';
foreach ($univers as $i => $u) {
    $html .= '<tr><td>' . ($i + 1) . '</td>'
        . '<td>' . $u['univerfull'] . '</td>'
        . '<td>' . $u['adress'] . ' ' . $u['zipcode'] . '</td>'
        . '</tr>';
}

$html .= '</tbody></table></div><div id="prorector">Перший проректор ДДТУ<br></div><div id="prorectorNAME">В.М. Гуляєв</div></div>';
if (filter_input(INPUT_GET, 'pdf')) {
    $pdf->getPdf($html);
} else {
    echo $html;
}