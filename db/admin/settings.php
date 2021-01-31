<?php
/**
 * @author    studyjquery
 * @copyright 2015
 *SHOW_PROGRAMA
 */

use zukr\base\Base;
use zukr\base\html\Html;
use zukr\setting\Setting;

require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';
header('Content-Type: text/html; charset=utf-8');
session_name('tzLogin');
session_start();

//Если есть доступ к странице
Base::init();
Base::setSNRCRF();
$params = Base::$param->getContainer();
if (!Base::$user->getUser()->isAdmin()) {
    Go_page('error');
}
Base::$app->cacheFlush();
?>
<!DOCTYPE html>
<html lang="ua">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="../css/style.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>
    <script type="text/javascript" src="../js/notify.js"></script>
    <script type="text/javascript" src="../js/params.js"></script>
    <script>
        var _SNRCRF = '<?=Base::$app->_snrcrf?>';
    </script>
    <title>Налаштування</title>
</head>
<body>
<header><a href="action.php">Меню</a></header>
<?php
echo '<form class="form" action="settings.php" method=POST><h2>Коротка інформація про систему</h2>'
    . '<h5>Обмеження розміру файлу на завантаження:' . ini_get('upload_max_filesize') . '</h5>'
    . '<h5>Кодування за замовчуванням:' . ini_get('default_charset') . "</h5>"
    . '<h5>Шляхи до підключення розширень dll/lib:' . ini_get('extension_dir') . '</h5>'
    . '<h5>Шляхи до файлів включення файлів php:' . ini_get('include_path') . '</h5>'
    . '<h5>Максимальний розмір POST запиту:' . ini_get('post_max_size') . '</h5>'
    . '
<table class="params">
        <tr><th>Налаштування</th><th>Значення</th><th>JS дія</th></tr>';
foreach ($params as $key => $p) {
    if ($p['type'] === Setting::BOOL) {
        $input = \zukr\base\html\HtmlHelper::checkboxStyled($key, '', $p['value']);
    } elseif (
        $p['type'] === Setting::STRING
        ||
        $p['type'] === Setting::INT
    ) {
        $input = Html::tag('input', null, ['value' => $p['value'], 'type' => 'text'])
            . Html::tag('button', '=', ['class' => 'btn']);
    }
    echo '<tr data-key="' . $key . '" data-type="' . $p['type'] . '"><td>' . $p['description'] . '</td><td>'
        . $input
        . '</td>'
        . '<td>' . Html::tag(
            'input',
            null,
            [
                'value' => $p['action'],
                'type' => 'text',
                'class' => 'js_action',
                'data' => [
                        'super-user' => Base::$user->getUser()->getLogin() === 'krupnik'
                            ? "true"
                            : "false"
                ],
                'disabled' => Base::$user->getUser()->getLogin() !== 'krupnik'
            ]
        ) . '</td>'
        . '</tr>';
}
echo '</table></form>'; ?>
<script>
    $.notify.defaults({
        position: 'top center',
        globalPosition: 'top center',
        gap: 8
    });
</script>
</body>
</html>