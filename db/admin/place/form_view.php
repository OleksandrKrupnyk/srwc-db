<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:35
 */

use zukr\base\Base;

$db = Base::$app->db;
const SUPERSQL = "
SELECT univer, t1.first,t2.second,t3.third,t4.diplom,t44.conf,t5.count_invitation,t6.count_takepart  
FROM `autors` 
LEFT JOIN 
	(select id_u, count(place) as first
     from autors  
     where place = 'I' 
     group by id_u) as t1 
ON autors.id_u = t1.id_u
LEFT JOIN
	(select id_u, count(place)as second from autors  
     where place = 'II' 
     group by id_u)as t2 
ON autors.id_u = t2.id_u
LEFT JOIN 
	(select id_u, count(place)as third 
     from autors  
     where place = 'III' group by id_u)as t3 
ON autors.id_u = t3.id_u
LEFT JOIN 
	(select id_u, count(place) as diplom 
    from autors  where place = 'D' and autors.arrival = '1' group by id_u) as t4 
ON autors.id_u = t4.id_u
LEFT JOIN 
	(select id_u, count(place) as conf 
    from autors  where autors.arrival = '1' group by id_u)as t44 
ON autors.id_u = t44.id_u
LEFT JOIN 
	(select autors.id_u, count(autors.place) as count_invitation
    from autors
    left join wa on wa.id_a = autors.id
    left JOIN works on wa.id_w = works.id
    where works.invitation = '1' group by id_u)as t5
ON autors.id_u = t5.id_u
LEFT JOIN 
	(select autors.id_u, count(autors.place) as count_takepart
    from autors
    left join wa on wa.id_a = autors.id
    left JOIN works on wa.id_w = works.id
    group by id_u)as t6 
ON autors.id_u = t6.id_u
LEFT JOIN univers ON univers.id=autors.id_u
GROUP BY univers.id ORDER BY `univer`;";

const SUPERSQL2 = "select ' ' as `univer`, 
sum(t.first) as `first`,
sum(t.second) as `second`,
sum(t.third) as `third`,
sum(t.diplom) as `diplom`,
sum(t.conf) as `conf`,
sum(t.count_invitation) as `count_invitation`,
sum(t.count_takepart) as `count_takepart`
FROM(
select t1.first,t2.second,t3.third,t4.diplom,t44.conf,t5.count_invitation,t6.count_takepart  
FROM autors 
LEFT JOIN 
	(select id_u, count(place) as first
     from autors  
     where place = 'I' 
     group by id_u) as t1 
ON autors.id_u = t1.id_u
LEFT JOIN
	(select id_u, count(place)as second from autors  
     where place = 'II' 
     group by id_u)as t2 
ON autors.id_u = t2.id_u
LEFT JOIN 
	(select id_u, count(place)as third 
     from autors  
     where place = 'III' group by id_u)as t3 
ON autors.id_u = t3.id_u
LEFT JOIN 
	(select id_u, count(place) as diplom 
    from autors  where place = 'D' and autors.arrival = '1' group by id_u) as t4 
ON autors.id_u = t4.id_u
LEFT JOIN 
	(select id_u, count(place) as conf 
    from autors  where autors.arrival = '1' group by id_u)as t44 
ON autors.id_u = t44.id_u
LEFT JOIN 
	(select autors.id_u, count(autors.place) as count_invitation
    from autors
    left join wa on wa.id_a = autors.id
    left JOIN works on wa.id_w = works.id
    where works.invitation = '1' group by id_u)as t5
ON autors.id_u = t5.id_u
LEFT JOIN 
	(select autors.id_u, count(autors.place) as count_takepart
    from autors
    left join wa on wa.id_a = autors.id
    left JOIN works on wa.id_w = works.id
    group by id_u)as t6 
ON autors.id_u = t6.id_u
LEFT JOIN univers ON univers.id=autors.id_u
GROUP BY autors.id_u
) as t ";
$query = SUPERSQL; //see file include
$results = $db->rawQuery($query);

$query = "SELECT CONCAT(autors.suname,' ',autors.name,' ',autors.lname) 
              AS fio FROM autors GROUP BY fio";
$db->withTotalCount()->rawQuery($query);
$count1 = (int)($db->totalCount);
$count2 = round($count1 * 0.25);
$query = SUPERSQL2;
$result = $db->rawQueryOne($query);
?>
<!-- просмотр результата распределения мест -->
<header>
    <a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a>
</header>
<header title="Розподіл призових місць серед вузів які брали участь у конференції">Розподіл призових місць серед
    авторів
</header>
<header style="width:fit-content;">
<a  href='action.php?action=place_edit'>Редагувати</a>
</header>

<table>
    <tr>
        <th>№</th>
        <th>ВНЗ</th>
        <th>I</th>
        <th>II</th>
        <th>III</th>
        <th>Диплом(учасника)</th>
        <th>Приїхали</th>
        <th>Запросили</th>
        <th>Подали роботи</th>
    </tr>
    <?php
    $i = 1;
    foreach ($results as $row) {
        $rowHtml = <<<__HTML__
<tr>
    <td>$i</td>
    <td>{$row['univer']}</td>
    <td>{$row['first']}</td>
    <td>{$row['second']}</td>
    <td>{$row['third']}</td>
    <td>{$row['diplom']}</td>
    <td>{$row['conf']}</td>
    <td>{$row['count_invitation']}</td>
    <td>{$row['count_takepart']}</td>
</tr>
__HTML__;
        echo $rowHtml;
        $i++;
    }
    echo "
<tr>
<th colspan=\"2\">РАЗОМ<br>авторів</th>
<th>{$result['first']}<br>" . round($count2 * 0.20) . "</th>
<th>{$result['second']}<br>" . round($count2 * 0.30) . "</th>
<th>{$result['third']}<br>" . ($count2 - round($count2 * 0.20) - round($count2 * 0.30)) . "</th>
<th>{$result['diplom']}</th><th>{$result['conf']}</th>
<th>{$result['count_invitation']}</th>
<th>{$result['count_takepart']}</th>
</tr>
</table>"
        . "<b>Всього студентів авторів</b>: $count1. 
Нагородити 25% від загальної кількості авторів наукових робіт (Р.VI п.1 Положення про конкурс) це складає <b>$count2</b>. 
Дипломами 1-го ступеня <b>" . round($count2 * 0.20)."</b>
, дипломами 2-го ступеня <b>" . round($count2 * 0.30) . "</b>
, дипломами 3-го ступеня <b>" . ($count2 - round($count2 * 0.20) - round($count2 * 0.30)) . "</b>.";
    ?>
    <p><a href="http://zakon.rada.gov.ua/laws/show/z0620-17">Дивитись Положення про конкурс </a></p>
    <!-- Окончание просмотра результата распределения мест -->