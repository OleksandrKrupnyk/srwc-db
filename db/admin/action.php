<?php
require 'config.inc.php';
require 'functions.php';
require  '../vendor/autoload.php';
use zukr\base\Base;
use zukr\menu\Menu;

Base::init();
read_settings();
$menuData = include 'menu.php';
$menu = new Menu($menuData);



header('Content-Type: text/html; charset=utf-8');
session_name('tzLogin');
session_start();
//Если есть доступ к странице
if ($_SESSION['access']) {
    //Сообщение об ошибке. Если оно пусто то на экран ничего не выводиться.
    $error_message = '';
    /**
     * Команды переданные по POST запросу
     */
    $actionPost = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    if (in_array($actionPost, [
        'autor_add',
        'autor_edit',
        'leader_add',
        'leader_edit',
        'work_add',
        'work_edit',
        'work_link',
        'all_add',
        'univer_edit',
        'file_add',
        'review_add',
        'review_edit',

    ])) {
        execute_post_action($actionPost);
    }
    /**
     * комманды переданные по GET Для обработки перед формирванием страницы
     * После каждой команды идет перенаправление на страницу
     */
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    if (in_array($action, [
        'work_unlink',
        'work_delete',
        'file_delete',
        'review_delete',
        'review_update'
    ])) {
        execute_get_action($action);
        // каждое действие заканчивается  header(...)
    };
} else /*Перенаправление на страничку обычных пользователей*/ {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="ua">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="../css/menustyle.css" type="text/css" rel="stylesheet"/>
    <link href="../css/phone.css" type="text/css" rel="stylesheet"/>
    <link href="../css/jquery-ui-1.10.3.custom.min.css" type="text/css" rel="stylesheet"/>
    <link href="../css/style.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-1.10.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>
    <script type="text/javascript" src="../js/menuscript.js"></script>
    <title>&quot;СНР 2018&quot;&copy;</title>
</head>
<body>
<?php //переменная для определения предка вызова сценария
$FROM = trim(urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
//print_r($FROM);
?>
<!-- Форма добавления работы-->
<?php if ($_GET['action'] === 'work_add'): include 'work/form_add.php'; ?>
    <!-- Форма добавления автора-->
<?php elseif ($_GET['action'] === 'autor_add'): include 'autor/form_add.php'; ?>
    <!-- Форма добавления керівника-->
<?php elseif ($_GET['action'] === 'leader_add'): include 'leader/form_add.php'; ?>
    <!-- Форма добавления всех сведений-->
<?php elseif ($_GET['action'] === 'all_add'): include 'all/form_add.php'; ?>
    <!-- Связывание работы -->
<?php elseif ($_GET['action'] === 'work_link'): include 'work/form_link.php'; ?>
    <!--Добавление рецензии -->
<?php elseif ($_GET['action'] === 'add_review'): include 'review/form_add.php'; ?>
    <!--Редактирование рецензии -->
<?php elseif ($_GET['action'] === 'review_edit'): include 'review/form_edit.php'; ?>
    <!--Просмотр рецензии -->
<?php elseif ($_GET['action'] === 'review_view'): include 'review/form_view.php'; ?>
    <!-- Редактирование автора -->
<?php elseif ($_GET['action'] === 'autor_edit'): include 'autor/form_edit.php'; ?>
    <!-- Редактирование руководителя -->
<?php elseif ($_GET['action'] === 'leader_edit'): include 'leader/form_edit.php'; ?>
    <!-- Редактирование работы -->
<?php elseif ($_GET['action'] === 'work_edit'): include 'work/form_edit.php'; ?>
    <!-- Редактирование данных университета -->
<?php elseif ($_GET['action'] === 'univer_edit'): include 'univer/form_edit.php'; ?>
    <!-- Редактирование списка университетов в которые следует направить первое информационное сообщение -->
<?php elseif ($_GET['action'] === 'univer_invite'): include 'univer/form_invite.php'; ?>
    <!-- Просмотр  таблици работ -->
<?php elseif ($_GET['action'] === 'all_view'): include 'all/form_view.php'; ?>
    <!--Формування списку запрошень журі-->
<?php elseif ($_GET['action'] === 'leader_invit'): include 'leader/form_invit.php'; ?>
    <!--Запрошення робіт-->
<?php elseif ($_GET['action'] === 'section_invite'): include 'section/form_invite.php'; ?>
    <!-- Отметки на регистрации в 3-м корпусе -->
    <!-- Отметки о прибытии на конкурс  -->
<?php elseif ($_GET['action'] === 'reception_edit'): include 'reception/form_edit.php'; ?>
    <!-- Список авторов работ -->
<?php elseif ($_GET['action'] === 'autor_list'): include 'autor/form_list.php'; ?>
    <!-- Список руководителей работ -->
<?php elseif ($_GET['action'] === 'leader_list'): include 'leader/form_list.php' ?>
    <!-- Список Рецензентов работ -->
<?php elseif ($_GET['action'] === 'reviewer_list'): include 'reviewer/form_list.php' ?>
    <!-- Список тезисов по секциям -->
<?php elseif ($_GET['action'] === 'tesis_list'): include 'tesis/form_list.php'; ?>
    <!-- Роспеределение секций по аудиториям-->
<?php elseif ($_GET['action'] === 'rooms_edit'): include 'rooms/form_edit.php'; ?>
    <!-- Распределение мест стреди студентов которые приехали на конференцию-->
<?php elseif ($_GET['action'] === 'place_edit'): include 'place/form_edit.php'; ?>
    <!-- просмотр результата распределения мест -->
<?php elseif ($_GET['action'] === 'place_view'): include 'place/form_view.php'; ?>
    <!-- Протокол засідання -->
<?php elseif ($_GET['action'] === 'protocol'): include 'protocol.php'; ?>
    <!-- Статистична довідка -->
<?php elseif ($_GET['action'] === 'statistic'): include 'statistic.php'; ?>
    <!-- Список на отправку писем-->
<?php elseif ($_GET['action'] === 'sentemail'): include 'ag_sentmail.php'; ?>
    <!-- Список на отправку Тестовая разработка -->
<?php elseif ($_GET['action'] === 'test'): include 'ag_test.php'; ?>

<?php else: ?>
    <header>Меню</header>
    <?=$menu->getMenu();?>
<?php endif; ?>
<footer><a href="index.php?logoff">Вийти</a></footer>
<div id="test"><?php echo $error_message; ?></div>
<div id="operator">Оператор :<span><?= $_SESSION['usr'] ?></span></div>
<autor>Krupnik&copy;</autor>
</body>
</html>