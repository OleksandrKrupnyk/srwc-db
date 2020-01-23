<?php

use zukr\univer\UniverHelper;

$univers = UniverHelper::getInstance()->getInvited();
$html = '<div class="adress2">
          <header>Список розсилки 1-го інформаційного повідомлення <br>
                  Всеукраїнського конкурсу студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</header>
     <div id="table">
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
$html .= '</tbody></table>
</div><div id="prorector">Перший проректор ДДТУ<br></div><div id="prorectorNAME">В.М. Гуляєв</div></div>';
echo $html;