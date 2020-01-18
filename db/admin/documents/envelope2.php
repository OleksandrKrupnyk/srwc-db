<?php
global $link;
$query = "SELECT univers.* 
          FROM univers 
              LEFT JOIN works ON univers.id = works.id_u 
          WHERE works.invitation = '1' AND univers.id != '1' 
          GROUP BY univers.univer 
          ORDER BY univers.univer;";
$result = mysqli_query($link, $query);
echo '<div class="envelope">';
while ($row = mysqli_fetch_array($result)) {
    $envelop = <<<__ENVELOP__
    <div id="fromAdress">
        <strong><ins>Всеукраїнський конкурс студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</ins></strong><br>
        <em>вул.&nbsp;Дніпробудівська,2 м.&nbsp;Кам’янське,
        <br>Дніпропетровська обл.</em><br><strong>51918</strong>
    </div>
    <div id="whomAdress">
        <strong><ins>{$row['univerfull']}</ins></strong><br>
        <em>{$row['adress']}</em><br>
        <strong>{$row['zipcode']}</strong>
    </div>
    <hr>
__ENVELOP__;
    echo $envelop;
}
echo '</div>';
