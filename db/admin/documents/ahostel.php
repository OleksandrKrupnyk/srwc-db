<?php
global $link;
$query = "SELECT autors.id AS autorNumber, 
                            CONCAT(autors.suname,' ',autors.name,' ',autors.lname) AS fio_a, 
                            univers.univerrod AS univer, 
                            univers.id AS id,
                            autors.curse AS curse
                            FROM autors
                              LEFT JOIN univers ON univers.id=autors.id_u
                              LEFT JOIN wa ON autors.id=wa.id_a
                              LEFT JOIN works ON wa.id_w = works.id
                              WHERE works.invitation = 1 AND univers.id <> '1'
                              ORDER BY univer,fio_a";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
echo '<h1>Список студентів на поселеня у гуртожитку</h1>';
$univer = $row['univer'];
echo '<div id="univer_title"><em>' . $row['univer'] . '</em></div>'
    . '<ol>'
    . '<li>' . $row['fio_a'] . '</li>';
while ($row = mysqli_fetch_array($result)) {
    if ($univer === $row['univer']) {
        echo '<li>' . $row['fio_a'] . '</li>';
    } else {
        $univer = $row['univer'];
        echo '</ol>';
        echo '<div id="univer_title"><em>' . $row['univer'] . '</em></div>' .
            '<ol>' .
            '<li>' . $row['fio_a'] . '</li>';
    }
}
echo '</ol>';