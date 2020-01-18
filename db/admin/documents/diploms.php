<?php
global $link;

//Дипломы
use zukr\base\helpers\PersonHelper;

$ph = \zukr\place\PlaceHelper::getInstance();
$query = "SELECT
                          autors.place,
                          univers.univerrod,
                          sections.section,
                          works.title,
                          autors.suname AS F,
                          autors.name AS I,
                          autors.lname AS O
                        FROM autors
                          JOIN univers ON univers.id = autors.id_u
                          JOIN wa ON wa.id_a = autors.id
                          JOIN works ON wa.id_w = works.id
                          JOIN sections ON works.id_sec = sections.id
                         WHERE autors.place <> 'D' AND autors.arrival = '1'
                        ORDER BY univers.univerrod";

$result = mysqli_query($link, $query)
or die('Полка запиту на друк дипломів: ' . mysqli_error($link));
$total = mysqli_num_rows($result);
if ($total !== 0) {
    while ($row = mysqli_fetch_array($result)) {
        echo '<div class="diplom">'
            . '<div class="line1">' . $ph->diplomString($row['place']) . '</div>'
            . '<div class="line2">НАГОРОДЖУЄТЬСЯ:</div>'
            . '<div class="line3">' . PersonHelper::student_ka($row['O']) . ' ' . $row['univerrod'] . '</div>'
            . '<div class="line4">' . $row['F'] . ' ' . $row['I'] . ' ' . $row['O'] . '</div>'
            . '<div class="line5">за наукову роботу:<br>&quot;' . $row['title'] . '&quot;</div>'
            . '<div class="line6"> у Всеукраїнському конкурсі студентських наукових <br> робіт ' . $settings->NYEARS . ' навчального року з галузі знань<br> &quot;Електротехніка та електромеханіка&quot;</div>'
            . '<div class="line7">Секція &quot;' . $row['section'] . '&quot;</div>'
            . '<div class="line8">Перший проректор ДДТУ,<br>Голова галузевої конкурсної комісії<br> д.т.н., професор<br></div>'
            . '<div class="line9"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>В.М.Гуляєв</div>'
            . '<div class="line10">м. Кам’янське ' . $settings->YEAR . '</div>' .
            '</div>';
    }
} else {
    echo '<mark>За данним запитом данних не знайдено!</mark>';
}
