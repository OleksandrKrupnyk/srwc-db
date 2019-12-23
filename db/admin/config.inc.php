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
        die('Не можу встановити з\'єднання : ('. mysqli_connect_errno().')'.mysqli_connect_error());
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