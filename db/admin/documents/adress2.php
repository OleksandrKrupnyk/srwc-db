<?php
global $link;
//ТОлько те работы у коротых есть приглашенные работы минус ДДТУ
$query = "SELECT univers.*
                      FROM univers
                      LEFT JOIN works ON univers.id = works.id_u
                      WHERE works.invitation = '1' AND univers.id != '1'
                      GROUP BY univers.univer
                      ORDER BY univers.univer ";
$result = mysqli_query($link, $query);
echo '<div class="adress2">'
    . '<header>Список розсилки 2-го інформаційного повідомлення <br>Всеукраїнського конкурсу студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</header>'
    . '<div id="table"><table>'
    . '<tr><th>№</th><th>Кому</th><th>Адреса</th></tr>';
$i = 1;
while ($row = mysqli_fetch_array($result)) {

    echo table_row_list_address2($i, $row);
    $i++;
    if (($i === 21) || ($i === 47)) {
        echo '</table></div>'
            . '<div class = "tableprotocol"></div>'
            . '<div class="adress2">'
            . '<div id="table"><table>'
            . '<tr><th>№</th><th>Кому</th><th>Адреса</th></tr>';
    }
}
echo '</table></div>'
    . '<div id="prorector">Перший проректор ДДТУ<br></div>'
    . '<div id="prorectorNAME">В.М. Гуляєв</div>'
    . '</div>';
