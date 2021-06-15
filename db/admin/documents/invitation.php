<?php

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\Html;
use zukr\pdf\PdfWrapper;
use zukr\template\TemplateNameDictionary;
use zukr\template\TemplateService;

$db = Base::$app->db;


$universList = $db->rawQuery("
SELECT
  w.*,
  univers.*
FROM (SELECT
    id_u,
    COUNT(w.id) AS count_take_part,
    SUM(CASE WHEN invitation = 1 THEN 1 ELSE 0 END) AS count_invitation
  FROM `works` AS w
  WHERE id <> 1
  GROUP BY id_u 
    HAVING count_invitation>0
    ) w
  LEFT JOIN univers
    ON w.id_u = univers.id
    ORDER BY univerfull ");

$staff = $db->rawQuery("
SELECT l.id_u,l.suname,l.name,l.lname,
  `positions`.`position`  
  FROM `leaders` AS l
  JOIN `positions` ON l.`id_pos` = `positions`.`id`
 WHERE l.`invitation` = '1'
ORDER BY  id_u,`suname`
");
$staff = ArrayHelper::group($staff, 'id_u');

$settings = Base::$param;
$invitations = '';
if (!empty($universList)) {
    $gerb = PrintGerb($empty = true);
    $template = (new TemplateService())
        ->getBlockByName(TemplateNameDictionary::INVITATION);
    $replaceService = (new \zukr\base\ReplacerService());
    foreach ($universList as $row) {
        $rector = (!empty($row['rector_r']))
            ? $row['rector_r']
            : "<mark><a href=\"action.php?action=univer_edit&id_u={$row['id_u']}\">ЗАПОВНІТЬ ДАНІ ПРО ВНЗ</a></mark>";
        $leaders = '';
        if (isset($staff[$row['id_u']]) && !empty($staff[$row['id_u']])) {
            $leadersList = $staff[$row['id_u']];
            $list = [];
            foreach ($leadersList as $leader) {
                $list[] = PersonHelper::getFullName($leader) . ', ' . $leader['position'];
            }
            $leaders .= '<div id="message2"><p>Запрошуємо взяти участь у роботі журі конкурсної комісії конференції представників вашого ВНЗ.</p>' . Html::ol($list) . '</div>';
        }
        $invitationItem = $replaceService->makeReplace($template, [
            '{@posada}' => $row['posada'],
            '{@univerrod}' => $row['univerrod'],
            '{@rector}' => $rector,
            '{@leaders}' => $leaders,
        ]);
        $invitation = <<<__INVITATION__
<div class="v_invitation_1">
    <!-- БЛАНК УНИВЕРСИТЕТА -->
    <img class= "hGERB" src ="./../img/gerb.png" alt="herb" style="margin-left: 7.8cm;">
    <div class = "hMON">МІНІСТЕРСТВО ОСВІТИ І НАУКИ УКРАЇНИ</div>
    <div class = "hDDTUfull">ДНІПРОВСЬКИЙ ДЕРЖАВНИЙ ТЕХНІЧНИЙ УНІВЕРСИТЕТ</div>
    <div class = "hDDTUshort">(ДДТУ)</div>
    <div class = "hADRESS">вул. Дніпробудівська, 2 м. Кам’янське, 51918, тел./факс (0569) 538523</div>
    <div class = "hMAIL">Е-mail: <span>science@dstu.dp.ua</span> код ЄДРПОУ 02070737</div>
    {$gerb}
    $invitationItem
</div>
<div class="page-break"></div> 
<!-- Окончание БЛАНК УНИВЕРСИТЕТА -->
__INVITATION__;
        $invitations .= $invitation;
    }
} else {
    $invitations .= '<mark>Помилка запиту даних.</mark>';
}
if (filter_input(INPUT_GET, 'pdf')) {
    $pdf = PdfWrapper::getInstance();
    $pdf->getPdf($invitations);
} else {
    echo $invitations;
}
