<?php
global $link;
if (
!empty(($badge = filter_input(INPUT_GET, 'badge', FILTER_VALIDATE_INT)))
) {
    $query = "
SELECT univers.univerfull AS univerfull, 
       concat(autors.name, '<br>', autors.suname) AS if_a, 
       autors.id AS a_id 
FROM autors 
    JOIN univers ON (autors.id_u = univers.id)
    WHERE autors.id = {$badge}";
} elseif (
!empty($listAutorsId = filter_input(INPUT_POST, 'works_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY))
) {
    sort($listAutorsId);
    $listAutors = '(' . \implode(',', $listAutorsId) . ')';
    $query = "
SELECT univers.univerfull AS univerfull, 
       concat(autors.name, '<br>', autors.suname) AS if_a, 
       autors.id AS a_id 
FROM autors 
    JOIN univers ON (autors.id_u = univers.id) 
WHERE autors.id IN {$listAutors} GROUP BY a_id;";
} elseif (!isset($_GET['badge'])) {
    $query = "
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
";
}
echo '<div class="badges">';
if (!empty($query)) {
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result)) {
        $badge = '<div class="badge"><div class="konkurs">Всеукраїнський конкурс СНР з галузі знань</div><div class="konkurs">&quot;Електротехніка та електромеханіка&quot;</div>';
        $badge .= '<div class="id-number">' . $row['a_id'] . '</div><div class="bif">' . $row['if_a'] . '</div></div>';
        echo $badge;
    }
}
echo '</div>';
