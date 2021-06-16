<?php

use zukr\base\Base;
use zukr\pdf\PdfWrapper;

$db = Base::$app->db;
if (
!empty(($badge = filter_input(INPUT_GET, 'badge', FILTER_VALIDATE_INT)))
) {
    $results = $db->rawQuery("
SELECT univers.univerfull AS univerfull, 
       concat(autors.name, '<br>', autors.suname) AS if_a, 
       autors.id AS a_id 
FROM autors 
    JOIN univers ON (autors.id_u = univers.id)
    WHERE autors.id = {$badge};");
} elseif (
!empty($listAutorsId = filter_input(INPUT_POST, 'works_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY))
) {
    sort($listAutorsId);
    $listAutors = '(' . implode(',', $listAutorsId) . ')';
    $results = $db->rawQuery("
SELECT univers.univerfull AS univerfull, 
       concat(autors.name, '<br>', autors.suname) AS if_a, 
       autors.id AS a_id 
FROM autors 
    JOIN univers ON (autors.id_u = univers.id) 
WHERE autors.id IN {$listAutors} GROUP BY a_id;");
} elseif (!isset($_GET['badge'])) {
    $results = $db->rawQuery("
SELECT works.id AS id, 
works.id_u AS id_u, univers.univerfull AS univerfull, 
    concat(autors.name,'<br>',autors.suname) 
  AS if_a, autors.id AS a_id 
FROM (((works join wa on(wa.id_w = works.id)) 
    join autors on(wa.id_a = autors.id))  
    join univers on(works.id_u = univers.id)) 
WHERE (works.invitation = '1' AND autors.bprint<>'1') 
GROUP BY works.id,a_id  
ORDER BY works.id,if_a;
");
} else {
    $results = [];
}
$content = '';
foreach ($results as $badge) {
    $content .= '
<div class="badge">
<div class="konkurs">Всеукраїнський конкурс СНР з галузі знань</div>
<div class="konkurs">&quot;Електротехніка та електромеханіка&quot;</div>
<div class="id-number">' . $badge['a_id'] . '</div>
<div class="bif">' . $badge['if_a'] . '</div>
</div>';
}
$content = '<div class="badges">' . $content . '</div>';
if (filter_input(INPUT_GET, 'pdf')) {
    $pdf = PdfWrapper::getInstance();
    $pdf->getPdf($content);
} else {
    echo $content;
}