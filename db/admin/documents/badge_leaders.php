<?php

use zukr\base\Base;
use zukr\pdf\PdfWrapper;

$db = Base::$app->db;
//бейджики руководителей
//Формируем запрос в БД
$query = "
SELECT 
       CONCAT(leaders.suname,' ',leaders.name,' ',leaders.lname) AS fio,
       univerfull,
       position,
       status,degree 
FROM leaders 
    JOIN univers ON leaders.id_u = univers.id 
    LEFT JOIN  positions ON leaders.id_pos = positions.id 
    LEFT outer join statuses ON leaders.id_sat = statuses.id 
    LEFT outer join degrees ON leaders.id_deg = degrees.id";
if (
!empty($listLeadersId = filter_input(INPUT_POST, 'works_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY))
) {
    sort($listLeadersId);
    $queryWhere = ' WHERE leaders.id IN (' . implode(',', $listLeadersId) . ') GROUP BY leaders.id';
    $query .= $queryWhere;

} else {
    $query .= (isset($_GET['badge']))
        ? " WHERE leaders.id = '{$_GET['badge']}' GROUP BY leaders.id"
        : " WHERE leaders.arrival='1' GROUP BY leaders.id";
    //echo $query;
}
if (!empty($query)) {
    $results = $db->rawQuery($query);
    $content = '';
    foreach ($results as $badge) {
        $str = '';
        if ($badge['degree'] !== '-немає-') {
            $str .= $badge['degree'];
            if ($badge['status'] !== '-немає-') {
                $str .= ', ' . $badge['status'];
            }
        } else {
            $str .= $badge['position'];
        }

        $content .= '
<div class="badge">
    <div>Всеукраїнський конкурс студентських наукових робіт з галузі знань</div>
    <div>&quot;Електротехніка та електромеханіка&quot;</div>
    <div class="buniverfull">' . $badge['univerfull'] . '</div>
    <div class="bfio">' . $str . '<br>' . $badge['fio'] . '</div>
</div>';
    }
}
$content = '<div class="badges">' . $content . '</div>';
if (filter_input(INPUT_GET, 'pdf')) {
    $pdf = PdfWrapper::getInstance();
    $pdf->getPdf($content);
} else {
    echo $content;
}
