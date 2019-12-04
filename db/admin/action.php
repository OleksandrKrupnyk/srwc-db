<?php
require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';

use zukr\base\Base;
use zukr\menu\Menu;

session_name('tzLogin');
session_start();
Base::init();
$settings = Base::$param;
$menuData = include 'menu.php';
$menu = new Menu($menuData);
if (isset($_SESSION['notify']['msg'])) {
    $_msg = $_SESSION['notify']['msg'] ?? '';
    $_type = $_SESSION['notify']['type'] ?? '';
    unset($_SESSION['notify']);
}
ob_start();

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
    }
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

        <title>&quot;СНР 2018&quot;&copy;</title>
    </head>
    <body>
    <?php //переменная для определения предка вызова сценария
    $FROM = trim(urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
    //print_r($FROM);
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    if (in_array($action, [
        'work_add',
        'autor_add',
        'leader_add',
        'all_add',
        'work_link',
        'review_add',
        'review_edit',
        'review_view',
        'autor_edit',
        'leader_edit',
        'work_edit',
        'univer_edit',
        'univer_invite',
        'all_view',
        'leader_invit',
        'section_invite',
        'reception_edit',
        'autor_list',
        'leader_list',
        'reviewer_list',
        'tesis_list',
        'rooms_edit',
        'place_edit',
        'place_view',
        'protocol_view',
        'statistic_view',
        'email_edit',
        'test_edit',
        'error_list',
    ])) {
        execute_get_action($action);
    } else {
        echo "<header>Меню</header>";
        echo $menu->getMenu();
    }
    ?>
    <footer><a href="index.php?logoff">Вийти</a></footer>
    <div id="test"><?= $error_message; ?></div>
    <div id="operator">Оператор :<span><?= $_SESSION['usr'] ?></span></div>
    <autor>Krupnik&copy;</autor>
    </body>
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-1.10.js"></script>
    <script type="text/javascript" src="../js/notify.js"></script>
    <script type="text/javascript" src="../js/menuscript.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>
    <script>
        $.notify.defaults({position: 'top center',elementPosition:'top center'});
        var _type = '<?=$_type?>'.toString();
        var _msg = '<?=$_msg?>'.toString();
        if (_msg !== '') {
            $.notify(_msg, _type);
        }
    </script>
    </html>
<?php
ob_flush();