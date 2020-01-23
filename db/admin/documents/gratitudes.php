<?php
global $link;
$settings = \zukr\base\Base::$param;
//Подяки
$query = "SELECT leaders.id,univers.univerrod AS univer, CONCAT(leaders.suname,' ',leaders.name,' ',leaders.lname) AS fio_l,position,status,degree 
                          FROM leaders  
                              JOIN univers on leaders.id_u = univers.id  
                              LEFT outer JOIN  positions ON leaders.id_pos = positions.id
                              LEFT outer join statuses ON leaders.id_sat = statuses.id 
                              LEFT outer join degrees ON leaders.id_deg = degrees.id 
                          WHERE ((leaders.arrival = '1') and (univers.id <> 1)) 
                          GROUP BY leaders.id_u,fio_l 
                          ORDER BY univer, fio_l";
$result = mysqli_query($link, $query)
or die('Полка запиту : ' . mysqli_error($link));
$total = mysqli_num_rows($result);
//echo $total;
if ($total !== 0) {
    while ($row = mysqli_fetch_array($result)) {
        echo '<div class="gratitudes">';
        $str = '';
        if ($row['degree'] !== '-немає-') {
            $str .= $row['degree'];
            if ($row['status'] !== '-немає-') {
                $str .= ', ' . $row['status'];
            }
        } else {
            $str .= $row['position'];
        }
        echo '<div class="line1">нагороджується ' . $str . '</div>'
            . '<div class="line2">' . $row['univer'] . '</div>'
            . '<div class="line3">' . strtoupper($row['fio_l']) . '</div>'
            . '<div class="line4">за активну участь в підготовці та проведенні підсумкової конференції</div>'
            . '<div class="line5">&quot;Електротехніка та електромеханіка - ' . $settings->YEAR . '&quot;</div>'
            . '<div class="line6">Всеукраїнському конкурсі студентських наукових <br> робіт ' . $settings->NYEARS . ' навчального року з галузі<br> &quot;Електротехніка та електромеханіка&quot;</div>'
            . '<div class="line8">Перший проректор ДДТУ,<br>Голова галузевої конкурсної комісії,<br> д.т.н., професор</div>'
            . '<div class="line9"><br><br>В.М.Гуляєв</div>'
            . '<div class="line10">м. Кам’янське ' . $settings->YEAR . '</div>'
            . '</div><hr>';
    }
} else {
    echo '<mark>За данним запитом данних не знайдено!</mark>';
}
