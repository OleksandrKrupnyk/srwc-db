<?php
/**
 * @author    studyjquery
 * @copyright 2015
 */

use zukr\base\Base;

require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';
header('Content-Type: text/html; charset=utf-8');
session_name('tzLogin');
session_start();
global $link;
global $FROM;
//переменная для определения предка вызова сценария
$FROM = trim(urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
// Прочитать насторйки программы из БД
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
        'badge_autors',
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
//$stylesheet = file_get_contents(__DIR__.'/../../css/print.css');
////var_dump($stylesheet);die();
//$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
//$fontDirs = $defaultConfig['fontDir'];
//
//$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
//$fontData = $defaultFontConfig['fontdata'];
//
//$mpdf = new \Mpdf\Mpdf([
//
//    'mode' => 'utf-8',
//    'format' => [210, 297],
//]);
//$mpdf->SetAuthor('Крупник');
//$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
////
//// Write some HTML code:
//$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
//// Output a PDF file directly to the browser
//$mpdf->Output();
ob_flush();
