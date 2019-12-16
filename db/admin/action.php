<?php
xdebug_time_index();
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
$actionPost = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
ob_start();

//Если есть доступ к странице
if (!$session->get('access')) {
    /*Перенаправление на страничку обычных пользователей*/
    Go_page('index.php');
}
//Сообщение об ошибке. Если оно пусто то на экран ничего не выводиться.
$error_message = '';
/**
 * Команды переданные по POST запросу
 */
if (in_array($actionPost, [
    'author_add',
    'author_edit',
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
$_msg = $session->getFlash('recordSaveMsg', '');
$_type = $session->getFlash('recordSaveType', '');
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
        <link href="../css/menustyle.min.css" type="text/css" rel="stylesheet"/>
        <link href="../css/phone.min.css" type="text/css" rel="stylesheet"/>
        <link href="../css/jquery-ui-1.10.3.custom.min.css" type="text/css" rel="stylesheet"/>
        <link href="../css/style.min.css" type="text/css" rel="stylesheet"/>
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../js/jquery-ui-1.10.js"></script>
        <script type="text/javascript" src="../js/notify.js"></script>
        <script type="text/javascript" src="../js/menuscript.js"></script>
        <script type="text/javascript" src="../js/comon.js"></script>
        <script type="text/javascript" src="../js/admin.js" async></script>
        <title>&quot;СНР 2018&quot;&copy;</title>
    </head>
    <body>
    <?php //переменная для определения предка вызова сценария

    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    if (in_array($action, [
							'work_add',
                           'work_link',
                           'work_edit',
                           'author_add',
                           'author_edit',
                           'author_list',
                           'leader_add',
                           'leader_edit',
                           'leader_invit',
                           'all_add',
                           'all_view',
                           'review_add',
                           'review_edit',
                           'review_view',
                           'reviewer_list',
                           'univer_edit',
                           'univer_invite',
                           'section_invite',
                           'reception_edit',
                           'leader_list',
                           'tesis_list',
                           'rooms_edit',
                           'place_edit',
                           'place_view',
                           'protocol_view',
                           'statistic_view',
                           'email_edit',
                           'test_edit',
                           'error_list',])) {
        execute_get_action($action);
    } else {
        $session->setFromParam();
        echo "<header>Меню</header>"
            . $menu->getMenu();
    }
    ?>
    <footer><a href="index.php?logoff">Вийти</a></footer>
    <div id="test"><?= 'from :' . urldecode($_SESSION['from']) ?><?= $error_message; ?></div>
    <div id="operator">Оператор :<span><?= $session->get('usr') ?></span></div>
    <autor class="autor"><?= 'xdebug_time_index :' . number_format(xdebug_time_index(), 3) . 'sec| xdebug_peak_memory_usage :' . number_format(xdebug_peak_memory_usage() / 1024 / 1024, 3) . 'MB| xdebug_memory_usage :' . number_format(xdebug_memory_usage() / 1024 / 1024, 3) . 'MB' ?>
        Krupnik&copy;
    </autor>
    <script>
        $.notify.defaults({
            position: 'top center',
            globalPosition: 'top center',
            gap: 8
        });
        var _type = '<?=$_type?>'.toString();
        var _msg = '<?=$_msg?>'.toString();
        console.log(_type, _msg);
        if (_msg !== '') {
            $.notify(_msg, _type);
        }
    </script>
    </body>

    </html>
<?php
ob_flush();