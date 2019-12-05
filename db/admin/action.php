<?php
require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';

use zukr\base\Base;
use zukr\menu\Menu;

session_name('tzLogin');
session_start();
Base::init();
$session = Base::$session;
$settings = Base::$param;
$menuData = include 'menu.php';
$menu = new Menu($menuData);
$_msg = $session->getFlash('recordSaveMsg', '');
$_type = $session->getFlash('recordSaveType', '');
ob_start();

//Если есть доступ к странице
if ($session->get('access')) {
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
    Go_page('index.php');
}
?>
    <!DOCTYPE html>
    <html lang="ua">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="icon" type="image/png" href="../images/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="../images/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="../images/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="../images/favicon-192x192.png" sizes="192x192">
        <link rel="manifest" href="manifest.json">
        <link href="../css/menustyle.css" type="text/css" rel="stylesheet"/>
        <link href="../css/phone.css" type="text/css" rel="stylesheet"/>
        <link href="../css/jquery-ui-1.10.3.custom.min.css" type="text/css" rel="stylesheet"/>
        <link href="../css/style.css" type="text/css" rel="stylesheet"/>
        <script type="text/javascript" src="../js/jquery.js"></script>
        <script type="text/javascript" src="../js/jquery-ui-1.10.js"></script>
        <script type="text/javascript" src="../js/notify.js"></script>
        <script type="text/javascript" src="../js/menuscript.js"></script>
        <title>&quot;СНР 2018&quot;&copy;</title>
    </head>
    <body>
    <?php //переменная для определения предка вызова сценария
    $FROM = trim(urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
    $session->set('from', $FROM);
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
    <div id="operator">Оператор :<span><?= $session->get('usr') ?></span></div>
    <autor>Krupnik&copy;</autor>
    <script type="text/javascript" src="../js/admin.js" async></script>
    <script>
        $.notify.defaults({position: 'top center', elementPosition: 'top center'});
        var _type = '<?=$_type?>'.toString();
        var _msg = '<?=$_msg?>'.toString();
        console.log(_type,_msg);
        if (_msg !== '') {
            $.notify(_msg, _type);
        }
    </script>
    </body>

    </html>
<?php
ob_flush();