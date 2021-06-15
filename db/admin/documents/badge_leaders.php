<?php
global $link;
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
echo '<div class="badges">';
if (!empty($query)) {
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result)) {
        $badge = '<div class="badge">'
            . '<div>Всеукраїнський конкурс студентських наукових робіт з галузі знань</div>'
            . '<div>&quot;Електротехніка та електромеханіка&quot;</div>'
            . '<div class="buniverfull">' . $row['univerfull'] . '</div>';
        $str = '';
        if ($row['degree'] !== '-немає-') {
            $str .= $row['degree'];
            if ($row['status'] !== '-немає-') {
                $str .= ', ' . $row['status'];
            }
        } else {
            $str .= $row['position'];
        }
        $badge .= '<div class="bfio">' . $str . '<br>' . $row['fio'] . '</div>';
        $badge .= '</div>';
        echo $badge;
    }
}
echo '</div>';
