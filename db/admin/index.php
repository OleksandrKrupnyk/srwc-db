<?php
/**
 */

use zukr\base\Base;
use zukr\log\Log;

header('Content-Type: text/html; charset=utf-8');
require 'config.inc.php';
require 'functions.php';
require '../vendor/autoload.php';
Base::init();
global $link;
$log = Log::getInstance();;

session_name('tzLogin');
session_set_cookie_params(2 * 7 * 24 * 60 * 60);
// Устанавливаем время жизни куки 2 недели
session_start();

if (isset($_GET['logoff'])) {
    $log->logAction('logoff', 'tz_members', '');
    $_SESSION = [];
    session_destroy();
    Go_page('index.php');
}


if (isset($_SESSION['access']) && !isset($_COOKIE['tzRemember']) && !$_SESSION['rememberMe']) {
    // Если вы вошли в систему, но куки tzRemember (рестарт браузера) отсутствует
    // и вы не отметили чекбокс 'Запомнить меня':
    $log->logAction('logoff', 'tz_members', '');
    $_SESSION = [];
    session_destroy();

    // Удалаяем сессию
}

/* Преход был из другой формы? */
if (isset($_POST['submit'])) {
    //Да
    $err = [];
    // Запоминаем ошибки
    if (!$_POST['username'] || !$_POST['password']) {
        $err[] = 'Все поля должны быть заполнены!';
    }
    // Проверяем заполненные поля.Поля заполнены?
    if (empty($err)) {
        //Да?
        $_POST['username'] = mysqli_real_escape_string($link, $_POST['username']);
        $_POST['password'] = mysqli_real_escape_string($link, $_POST['password']);
        $_POST['rememberMe'] = (int)$_POST['rememberMe'];
        // Получаем все ввденые данные

        $query = "SELECT id,usr\n"
            . "FROM tz_members WHERE usr='" . $_POST['username'] . "' AND pass='" . md5($_POST['password']) . "'";
        $result = mysqli_query($link, $query)
        or die('Невірний запрос до бази данних: ' . mysqli_error($link));

        $row = mysqli_fetch_array($result);

        if (!empty($row['usr'])) {
            // Если все в порядке - входим в систему
            $_SESSION['usr'] = $row['usr'];
            $_SESSION['id'] = $row['id'];
            $log->logAction('login', 'tz_members', '');
            $_SESSION['rememberMe'] = $_POST['rememberMe'];
            $_SESSION['access'] = 'YES';
            $_SESSION['user_id'] = $row['id'];
            // Сохраняем некоторые данные сессии
            setcookie('tzRemember', $_POST['rememberMe']);
            Go_page('action.php');
        } else {
            $err[] = 'Ошибочный пароль или/и имя пользователя!';
        }
    }
    if ($err) {
        $_SESSION['msg']['login-err'] = implode('<br />', $err);
    }
    // Сохраняем сообщение об ошибке сессии
    Go_page('index.php');
}//if
?>
<!DOCTYPE html >
<html lang="ua">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="icon" type="image/png" href="../images/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="../images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../images/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="../images/favicon-192x192.png" sizes="192x192">
    <link rel="manifest" href="manifest.json">
    <link href="../css/login.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <title>Реєст <?= Base::$app->app_name ?></title>
</head>
<?php if (!isset($_SESSION['access'])) : ?>
    <!-- Форма авторизации на страничке -->
    <body>
    <form method='post' action='' class="ui-form">
        <h3>Вхід<br><?= Base::$app->app_name ?></h3>
        <?php
        if (isset($_SESSION['msg']['login-err'])) {
            echo "<div class='err'>{$_SESSION['msg']['login-err']}</div>";
            unset($_SESSION['msg']['login-err']);
        }
        ?>
        <div class="form-row">
            <input type="text" name="username" id="username" required><label for="username">Логін</label>
        </div>
        <div class="form-row">
            <input type="password" name="password" id="password" required><label for="password">Пароль</label>
        </div>
        <input name="rememberMe" id="rememberMe" type="checkbox" value="1" checked><label for="rememberMe">Запамятати
            мене</label>
        <button name="submit" class="bt_login" value="login">Увійти</button>
        <input type="button" value="Реєстр" onclick="window.location='./../app/index.php'">
        <input type="button" value="Сайт" onclick="window.location='./../../konkurs/'">
    </form>
    </body>
<?php else: ?>
    <header><a href="action.php" title="Торжественно клянусь, что замышляю только шалость!">Працювати</a></header>
    <span></span>
    <footer><a href="index.php?logoff" title="Доббі вільний!">Вийти</a></footer>
<?php endif; ?>
</html>