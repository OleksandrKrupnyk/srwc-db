<?php
ini_set ('default_charset' , 'UTF-8' );
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'elm_c2018');
define('N_LEADERS',1);//Максимальное количество руководителей на 1 работу
define('N_AUTORS',2);//Максимальное количество студентов авторов на 1 работу
$timecooke=60;

define('DIR', './../files/');
$dir= './../files/';

define('IMGDIR', './../img/');
$imgdir= './../img/';

//Определенние переменной в 4 пустых знакоместа
define('TAB_SP', '&nbsp;&nbsp;&nbsp;&nbsp;');
$TAB_SP= '&nbsp;&nbsp;&nbsp;&nbsp;';

//Файл додатка до приглашения
define('APENDEX2', '../img/application_2.pdf');

//Глобальное соединение с базой данных
$link = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD,DB_NAME);
    if (!$link) {
        die('Не можу встановити з\'эднання : ('. mysqli_connect_errno().')'.mysqli_connect_error());
    }
//Переменные настроек по умолчанию
$settings['SHOW_DB_TABLE'] = '0';
$settings['SHOW_PROGRAMA'] = '0';
$settings['PRINT_DDTU_HEADER'] = '0';
$settings['SHOW_RAITING'] = '0';
$settings['ALLOW_EMAIL'] = '0';
$settings['INVITATION'] = '0';
// Наказ про місце проведення (ВНЗ проведення)
$settings['DATEPL']= '10.10.2017';
$settings['ORDERPL']= '1364';
// Наказ про Положення
$settings['DATEPO']= '18.04.2017';
$settings['ORDERPO']= '605';

$INVITELEADERS = "Відповідно до наказу Міністерства освіти і науки України від {$settings['DATEPL']} року № {$settings['ORDERPL']}  
Дніпровський державний технічний університет є базовим для проведення другого етапу Всеукраїнського 
конкурсу студентських наукових робіт з галузі та спеціалізації «Електротехніка та електромеханіка». Для визначення переможців з 15 по 18 квітня 2018 року 
проводиться підсумкова студентська науково-технічна конференція.";


$error_message = '';


//Определение сложных запросов
define('SUPERSQL',"select univer, t1.first,t2.second,t3.third,t4.diplom,t44.conf,t5.count_invitation,t6.count_takepart  
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
GROUP BY autors.id_u ORDER BY univer");



define('SUPERSQL2',"select ' ' as `univer`, 
sum(t.first) as `first`,
sum(t.second) as `second`,
sum(t.third) as `third`,
sum(t.diplom) as `diplom`,
sum(t.conf) as `conf`,
sum(t.count_invitation) as `count_invitation`,
sum(t.count_takepart) as `count_takepart`
FROM(
select univer, t1.first,t2.second,t3.third,t4.diplom,t44.conf,t5.count_invitation,t6.count_takepart  
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
GROUP BY autors.id_u ORDER BY univer
) as t ");

define('SUPERSQL3',"select univerfull,t4.allworks,t6.students,t1.first,t2.second,t3.third,t7.count_invitation
FROM autors
LEFT JOIN
  (select works.id_u, count(works.title) as allworks  from works
    group by works.id_u) as t4  ON autors.id_u = t4.id_u
LEFT JOIN (select id_u, count(place) as first from autors where place = 'I' group by id_u) as t1 ON autors.id_u = t1.id_u
LEFT JOIN (select id_u, count(place) as second from autors where place = 'II' group by id_u) as t2 ON autors.id_u = t2.id_u
LEFT JOIN (select id_u, count(place) as third from autors where place = 'III' group by id_u) as t3 ON autors.id_u = t3.id_u
LEFT JOIN (select autors.id_u, count(autors.place) as students  from autors
    left join wa on wa.id_a = autors.id
    left JOIN works on wa.id_w = works.id
  group by id_u)as t6
    ON autors.id_u = t6.id_u
LEFT JOIN
  (select autors.id_u, count(autors.place) as count_invitation
   from autors
     left join wa on wa.id_a = autors.id
     left JOIN works on wa.id_w = works.id
   where works.invitation = '1' group by id_u)as t7
    ON autors.id_u = t7.id_u
    
LEFT JOIN univers ON univers.id=autors.id_u
GROUP BY autors.id_u ORDER BY univer");