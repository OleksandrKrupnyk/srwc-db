<?php
global $link;

use zukr\base\Base;

$settings = Base::$param;
$query = "SELECT 
       v_take_part.id_u AS id_u, 
       v_take_part.count_take_part AS count_take_part,
        v_invitation.count_invitation AS count_invitation, 
       univers.univerrod AS univerrod, 
       univers.rector_r AS rector_r, 
       univers.posada AS posada 
                FROM `v_take_part` 
                    LEFT JOIN v_invitation on v_take_part.id_u = v_invitation.id_u 
                    LEFT JOIN univers on v_take_part.id_u = univers.id
                    WHERE (univers.id <> '1') AND (count_invitation > 0) 
                    ORDER BY univerfull ";
//echo $query;
$result = mysqli_query($link, $query);
if (!empty($result)) {
    $total = mysqli_num_rows($result);
    if ($total !== 0) {
        echo '<div class="v_invitation_1">';
        while ($row = mysqli_fetch_array($result)) {
            $rector = (!empty($row['rector_r']))
                ? $row['rector_r']
                : "<mark><a href=\"action.php?action=univer_edit&id_u={$row['id_u']}&FROM={$FROM}\">ЗАПОВНІТЬ ДАНІ ПРО ВНЗ</a></mark>";
            //$invitation = ($row['count_invitation'] != '') ? $row['count_invitation'] : "<mark><a href=\"action.php?action=all_view#id_u{$row['id_u']}\">ЗАПРОСИТИ?</a></mark>";
            $invitation = '';
            // Печатать Шапку университета
            PrintGerb($empty = true);//Печатет данные бланка Герб и т.д.
            $blk_rectory = "<div id='rectory'>{$row['posada']} {$row['univerrod']}<br>{$rector}</div>";
            echo $blk_rectory;
            $blk_message = '<div id="message"><p>Галузева конкурсна комісія Всеукраїнського конкурсу студентських наукових робіт'
                . ' з галузі &quot;Електротехніка та електромеханіка&quot; запрошує до участі у підсумковій науково-практичній конференції авторів кращих робіт. </p>'
                . '<p>Список запрошених авторів наукових робіт наведено у Додатку 1.</p>'
                . '<p>Відповідно до &quot;Положення про  проведення Всеукраїнського конкурсу студентських наукових робіт  з природничих,'
                . " технічних та гуманітарних наук&quot; від {$settings->DATEPO} №{$settings->ORDERPO} автор наукової роботи, який  не  брав  участі  у  підсумковій"
                . ' науково-практичній конференції, не може бути претендентом на нагородження.</p>'
                . '</div>';
            echo $blk_message;
            $query2 = "SELECT leaders.invitation  FROM leaders WHERE id_u = '{$row['id_u']}' AND invitation = TRUE";
            $result2 = mysqli_query($link, $query2)
            or die('Invalid query: ' . mysqli_error($link));
            $count = mysqli_num_rows($result2);
            // Список журі від ВНЗ
            if (0 !== $count) {
                echo '<div id="message2"><p>Запрошуємо взяти участь у роботі журі конкурсної комісії конференції представників вашого ВНЗ.</p>';
                list_leaders_invite($row['id_u'], false);
                echo '</div>';
            }
            echo '<div id="message2"><p>Інформація про підсумкову конференцію наведена у Додатку 2.</p></div>'
                . '<div id="podpis">Перший проректор ДДТУ,<br>Голова галузевої конкурсної комісії<br><br>_______________   В.М.Гуляєв</div><hr>';
        }
        echo '</div>';
    } else {
        echo '<mark>За данним запитом данних не знайдено! <br> Встановіть відмітку про запрошення хоча б в одній роботі.</mark>';
    }
} else {
    echo '<mark>Помилка запиту даних.</mark>';
}