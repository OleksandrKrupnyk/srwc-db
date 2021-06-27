<?php
/**
 * @author    studyjquery
 * @copyright 2015
 */

use zukr\base\Base;

require 'config.inc.php';
require '../vendor/autoload.php';
header('Content-Type: text/html; charset=utf-8');
session_name('tzLogin');
session_start();
global $FROM;
//переменная для определения предка вызова сценария
$FROM = trim(urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
// Прочитать настройки программы из БД
Base::init();
$settings = Base::$param;
if (Base::$user->getUser()->isGuest()) {
    Go_page('index.php');
}
ob_start();
?>
<!DOCTYPE html>
<html lang="ua">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="icon" type="image/png" href="../images/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="../images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../images/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="../images/favicon-192x192.png" sizes="192x192">
    <link rel="manifest" href="manifest.json">
    <link href="../css/print.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/admin.js" async></script>
    <title>ДРУКУВАТИ <?= Base::$app->app_name ?></title>
</head>
<body>
<?php
try {
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    if (in_array($action, [
        'adress',
        'adress2',
        'envelope',
        'envelope2',
        'invitation',
        'invitation2',
        'ahostel',
        'badge_authors',
        'badge_leaders',
        'charters',
        'diploms',
        'gratitudes',
        'lhostel',
    ], true)) {
        execute_print_action($action);
    } else {
        Go_page('action.php');
    }
} catch (Exception $e) {
    Base::$log->critical($e->getMessage());
}
?>
</body>
</html>
<?php
ob_flush();
