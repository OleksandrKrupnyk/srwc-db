<?php
/**
 */

use zukr\base\Base;
use zukr\log\Log;
use zukr\login\LoginForm;

header('Content-Type: text/html; charset=utf-8');
require 'config.inc.php';
require '../vendor/autoload.php';
Base::init();
$db = Base::$app->db;
$log = Log::getInstance();;
Base::$session->setName('tzLogin');
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
}

/* Переход был из другой формы? */
if (filter_has_var(INPUT_POST, 'submit')) {
    //Да
    $err = [];
    // Запоминаем ошибки
    if (!(
        filter_has_var(INPUT_POST, 'username')
        &&
        strlen(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING)) > 0
    )) {
        $err[] = 'Логін не переданий';
    }
    if (!(filter_has_var(INPUT_POST, 'password') &&
        strlen(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING)) > 0)) {
        $err[] = 'Пароль не переданий';
    }
    // Проверяем заполненные поля.Поля заполнены?
    if (empty($err)) {
        $loginForm = new LoginForm(
            $_POST['username'],
            md5($_POST['password']),
            $_POST['rememberMe']
        );
        if ($loginForm->validate()) {
            // Если все в порядке - входим в систему
            $_SESSION['usr'] = $loginForm->userName;
            $_SESSION['id'] = $loginForm->getId();
            $log->logAction('login', 'tz_members', '');
            $_SESSION['rememberMe'] = $loginForm->rememberMe;
            $_SESSION['access'] = 'YES';
            $_SESSION['user_id'] = $loginForm->getId();
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
}
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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/icofont.min.css">
    <title>Реєстр <?= Base::$app->app_name ?></title>
</head>
<body>

<?php
if (Base::$session->get('access') === null) : ?>
    <!-- Форма авторизации на страничке -->
    <div class="container">
        <div class="row">
            <div class="col-5 m-auto">
                <form method='post' action='index.php'>

                    <h3 class="text-center"><i class="h3 icofont-company"></i> Вхід <?= Base::$app->app_name ?></h3>
                    <?php
                    if (isset($_SESSION['msg']['login-err'])) {
                        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
{$_SESSION['msg']['login-err']}
<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
                        unset($_SESSION['msg']['login-err']);
                    }
                    ?>
                    <div class="mb-3">
                        <label class="form-label" for="username">Логін</label>
                        <div class="input-group">
                            <div class="input-group-text"><i class="icofont-user-alt-3"></i></div>
                            <input class="form-control" type="text" name="username" id="username"
                            >
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password"> Пароль</label>
                        <div class="input-group">
                            <div class="input-group-text"><i class="icofont-ui-password"></i></i></div>
                            <input class="form-control" type="password" name="password" id="password">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" name="rememberMe" id="rememberMe" type="checkbox" value="1"
                               checked/>
                        <label class="form-check-label" for="rememberMe">Запам'ятати мене</label>
                    </div>
                    <div class="mb-3">
                        <button name="submit" class="btn btn-primary w-100" value="login"><i class="icofont-login"></i>
                            Увійти
                        </button>
                    </div>
                    <div class="mb-3">
                        <a class="btn btn-primary w-100"
                           href="../app/index.php"><i class="icofont-list"></i> Реєстр</a>
                    </div>
                    <div class="mb-3">
                        <a class="btn btn-primary w-100" href="../../konkurs/"><i class="icofont-brand-joomla"></i> Сайт</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php else: ?>
    <header><a href="action.php" title="Торжественно клянусь, что замышляю только шалость!">Працювати</a></header>
    <span></span>
    <footer><a href="index.php?logoff" title="Доббі вільний!">Вийти</a></footer>
<?php endif; ?>
</body>
<script src="../js/bootstrap.bundle.min.js"></script>
</html>