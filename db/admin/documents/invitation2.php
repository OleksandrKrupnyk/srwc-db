<?php
global $link;
$query = "SELECT autors.id as autorNumber, 
                                 CONCAT(autors.suname,' ',autors.name,' ',autors.lname) as fio_a, 
                                 univers.univerrod as univer, 
                                 univers.id as id,
                                 autors.curse as curse
                            FROM autors
                              LEFT JOIN univers ON univers.id=autors.id_u
                              LEFT JOIN wa ON autors.id=wa.id_a
                              LEFT JOIN works ON wa.id_w = works.id
                              WHERE works.invitation = 1 AND univers.id <> '1'
                              ORDER BY univer,fio_a";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
/*
$blk_golova = "<div id=\"podpis\">\nГолова галузевої конкурсної комісії,<br>\n"
    . "професор кафедри електротехніки та електромеханіки ДДТУ<br><br>\n"
    . "___________ О.В.Садовой</div>";*/
/*$blk_golova = "<div id=\"podpis\">Перший проректор ДДТУ,<br>\nГолова галузевої конкурсної комісії<br><br>\n_______________   В.М.Гуляєв</div>\n";*/
$blk_golova = '<div id="podpis">Заступник голови голови галузевої конкурсної комісії,<br>завідувач кафедри електротехніки та електромеханіки ДДТУ<br><br>_______________   В.Б.Нізімов</div>';
/*$blk_podpis=""; Раскоментировать для печати отчета*/
$blk_message = '<div id="message"><p>'
    . 'запрошених на підсумкову науково-практичну конференцію Всеукраїнського конкурсу студентських наукових робіт'
    . ' з галузі &quot;Електротехніка та електромеханіка&quot;'
    . "</p>"
    . "</div>";
$rowStudent = "<li>%s, (№%s)</li>";
$rowStudentArray = [$row['fio_a'], $row['autorNumber']];
/* начало формирования списка документов */
echo '<div class="v_invitation_2">';
/*Пишем первый раз*/
echo '<div id="application1">Додаток 1</div><div id="listsudents_title"><strong>Список студентів</strong></div>';
//Запомним текущий универ чтобы не повторять
$univer = $row['univer'];
echo "<div id=\"univer_title\"><em>{$row['univer']}</em></div>"
    . $blk_message
    . '<ol>';
vprintf($rowStudent, $rowStudentArray);
while ($row = mysqli_fetch_array($result)) {
    $rowStudentArray = [$row['fio_a'], $row['autorNumber']];
    if ($univer === $row['univer']) {
        vprintf($rowStudent, $rowStudentArray);
    } else {
        echo '</ol>'
            . $blk_golova
            . '<div id="podpis_image"></div>' .
            '<hr>'
            . '<div id="application1">Додаток 1</div>'
            . '<div id="listsudents_title"><strong>Список студентів</strong></div>';
        $univer = $row['univer'];
        echo '<div id="univer_title"><em>' . $row['univer'] . '</em></div>' . $blk_message . '<ol>';
        vprintf($rowStudent, $rowStudentArray);
    }

}
echo '</ol>' . $blk_golova . '</div>';