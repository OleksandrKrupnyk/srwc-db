<?php
require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';

use DebugBar\DataCollector\MessagesCollector;
use DebugBar\StandardDebugBar;
use zukr\base\Base;
use zukr\menu\Menu;

session_name('tzLogin');
session_start();
Base::init();
//Если есть доступ к странице
if (Base::$user->getUser()->isGuest()) {
    Go_page('index.php');
}
$session = Base::$session;
$settings = Base::$param;
$debugbar = new StandardDebugBar();
$debugbarRenderer = $debugbar->getJavascriptRenderer('/../db/vendor/maximebf/debugbar/src/DebugBar/Resources');
$debugbar->addCollector(new MessagesCollector('logging'));
$debugbar['logging']->aggregate(new DebugBar\Bridge\MonologCollector(Base::$log));
$debugbar['logging']->aggregate(new DebugBar\Bridge\MonologCollector(Base::$app->db->getLogger()));
$debugbar->addCollector(new DebugBar\DataCollector\ConfigCollector($settings->getAllsettingValue()));
$actionPost = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
ob_start();
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
    'section_add',
    'section_edit',
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
        <title><?= Base::$app->app_name ?></title>
        <link rel="icon" type="image/png" href="../images/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="../images/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="../images/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="../images/favicon-192x192.png" sizes="192x192">
        <link rel="manifest" href="manifest.json">
        <link href="../css/menustyle.min.css" type="text/css" rel="stylesheet"/>
        <link href="../css/phone.min.css" type="text/css" rel="stylesheet"/>
        <link href="../css/jquery-confirm.min.css" type="text/css" rel="stylesheet"/>
        <link href="../css/style.css" type="text/css" rel="stylesheet"/>
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../js/notify.js"></script>
        <script type="text/javascript" src="../js/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="../js/menuscript.js"></script>
        <script type="text/javascript" src="../js/comon.js"></script>
        <script type="text/javascript" src="../js/admin.js" async></script>
        <script>

            jconfirm.defaults = {
                useBootstrap: false,
                theme: 'supervan',
            };

        </script>
        <?php echo $debugbarRenderer->renderHead() ?>
    </head>
    <body id="top">
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
        'section_list',
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
        $menuData = include 'menu.php';
        $menu = new Menu($menuData);
        $session->setFromParam();
        echo "<header>Меню</header>"
            . $menu->getMenu();
    }
    ?>
    <div class="upPageLink pointer" id="btnUp" onclick="window.scrollTo(0, 0);">вгору</div>
    <footer><a href="index.php?logoff">Вийти</a></footer>
    <div id="test"><?= 'from :' . urldecode($_SESSION['from']) ?><?= $error_message; ?></div>
    <div id="operator">Оператор :<span><?= Base::$user->getUser()->getLogin() ?></span>
        <span><?= Base::$user->getUser()->isAdmin() ? 'A' : '' ?></span>
        <span><?= Base::$user->getUser()->isReview() ? 'R' : '' ?></span>
    </div>
    <autor class="autor">Krupnik&copy;</autor>
    <script>
        jconfirm.defaults = {
            useBootstrap: false,
            theme: 'supervan',
        };
        $.notify.defaults({
            position: 'top center',
            globalPosition: 'top center',
            gap: 8
        });
        let _type = '<?=$_type?>'.toString(),
            _msg = '<?=$_msg?>'.toString();
        if (_msg !== '') {
            $.notify(_msg, _type);
        }
    </script>
    <?= $debugbarRenderer->render() ?>
    </body>
</html>
<?php
ob_flush();