<?php
global $link;
$query = "SELECT univers.univerfull, univers.adress, univers.zipcode 
                      FROM univers WHERE univers.invite = '1' GROUP BY univerfull ASC";
$result = mysqli_query($link, $query);
echo '<div class="adress2">
                            <header>Список розсилки 1-го інформаційного повідомлення <br>
                            Всеукраїнського конкурсу студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</header>
                            <div id="table">
                            <table><tr><th>№</th><th>Кому</th><th>Адреса</th></tr>';
$i = 1;
while ($row = mysqli_fetch_array($result)) {
    echo table_row_list_address2($i, $row);
    $i++;
    if (($i === 23) || ($i === 48)) {
        echo '</table></div><div class="tableprotocol"></div>
<div class="adress2"><div id="table"><table><tr><th>№</th><th>Кому</th><th>Адреса</th></tr>';
    }
}
echo '</table>
</div><div id="prorector">Перший проректор ДДТУ<br></div><div id="prorectorNAME">В.М. Гуляєв</div></div>';
