<?php
require 'config.inc.php';
require 'functions.php';
read_settings();
header("Content-Type: text/html; charset=utf-8");
session_name('tzLogin');
session_start();
//Если есть доступ к странице
if ($_SESSION['access']) {
    //Сообщение об ошибке. Если оно пусто то на экран ничего не выводиться.
    $error_message = "";
    switch ($_POST['action']) {
        /*Добавление автора работы*/
        case "autor_add" : { include "a_autor_add.php"; }break;
        /*Добавление руководителя работы*/
        case "leader_add": { include "a_leader_add.php";}break;
        /*Добавление данных работы*/
        case "work_add": { include "a_work_add.php"; } break;
        /*Добавление всех ведомостей*/
        case "all_add": { include "a_all_add.php";}break;
        /*Завершение обработки связывния таблиц*/
        case "work_link": {include "a_work_link.php";}break;
        /*Изменение данных автора*/
        case "autor_edit": {include "a_autor_edit.php";} break;
        /*Редактирование руководителя*/
        case "leader_edit": {include "a_leader_edit.php";}break;
        /*Редактирование работы*/
        case "work_edit": {include "a_work_edit.php";} break;
        /*редактирование данных вуза*/
        case "univer_edit": {include "a_univer_edit.php";} break;
        /* Загрузка файла для работы*/
        case "work_edit_add_file": { include "a_work_edit_add_file.php";} break;
        /* Добавление рецензии */
        case "review_add":{ include  "a_review_add.php";}break;
        /* Редактирование рецензии */
        case "review_edit":{ include  "a_review_edit.php";}break;
        /*Рассылка сообщений*/
        case "sentemail_begin":{  }break;
    }// Завершение Обработка switch POST
    /***********************************************************************
     * комманды переданные по GET Для обработки перед формирванием страницы
     * /***********************************************************************/
    /*Обработка запросов на удаление связей между работой и руководителем или автором*/
    switch ($_GET['action']) {
        /* отвязывание*/
        case "unlink": {include "ag_unlink.php";} break;
        /* удаление работы*/
        case "delete_work": { include "ag_delete_work.php";} break;
        /*удаление файла работы*/
        case "delete_file": { include "ag_delete_file.php";} break;
        /*удаление рецензии*/
        case "delete_review": { include "ag_delete_review.php";} break;
        /*обновление балов в таблице works*/
        case "works_update_balls": { include "ag_works_update_balls.php";} break;
    }

} else
    /*Перенаправление на страничку обычных пользователей*/
    header("Location: index.php");
?>
<!DOCTYPE html>
<html>
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
$FROM = trim(urlencode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
//print_r($FROM);
?>
    <!-- Форма добавления работы-->
<?php     if ($_GET['action'] == "work_add"):  include 'ag_form_add_work.php'; ?>
    <!-- Форма добавления автора-->
<?php elseif ($_GET['action'] == "autor_add"): include 'ag_form_add_autor.php'; ?>
    <!-- Форма добавления керівника-->
<?php elseif ($_GET['action'] == "leader_add"): include 'ag_form_add_leader.php'; ?>
    <!-- Форма добавления всех сведений-->
<?php elseif ($_GET['action'] == "add_all"):   include 'ag_form_add_all.php'; ?>
    <!-- Связывание работы -->
<?php elseif ($_GET['action'] == "work_link"):  include "ag_work_link.php";?>
    <!--Добавление рецензии -->
<?php elseif ($_GET['action'] == "add_review"):   include 'ag_form_add_review.php';?>
    <!--Редактирование рецензии -->
<?php elseif ($_GET['action'] == "review_edit"):  include "ag_form_edit_review.php";?>
    <!--Просмотр рецензии -->
<?php elseif ($_GET['action'] == "review_view"):  include "ag_form_view_review.php";?>
    <!-- Редактирование автора -->
<?php elseif ($_GET['action'] == "autor_edit"): include 'ag_form_edit_autor.php'; ?>
    <!-- Редактирование руководителя -->
<?php elseif ($_GET['action'] == "leader_edit"): include 'ag_form_edit_leaders.php'; ?>
    <!-- Редактирование работы -->
<?php elseif ($_GET['action'] == "work_edit"): include 'ag_form_edit_work.php'; ?>
    <!-- Редактирование данных университета -->
<?php elseif ($_GET['action'] == "univer_edit"): include 'ag_form_edit_univer.php'; ?>
    <!-- Редактирование списка университетов в которые следует направить первое информационное сообщение -->
<?php elseif ($_GET['action'] == "univer_invite"): include "ag_univer_invite.php";?>
    <!-- Просмотр  таблици работ -->
<?php elseif ($_GET['action'] == "view"): include "ag_view.php";?>
    <!--Формування списку запрошень журі-->
<?php elseif ($_GET['action'] == "invit_leaders"): include "ag_invit_leaders.php";?>
    <!--Запрошення робіт-->
<?php elseif ($_GET['action'] == "invit_section"): include "invit_section.php";?>
    <!-- Отметки на регистрации в 3-м корпусе -->
<?php elseif ($_GET['action'] == "reception"): ?>
    <!-- Отметки о прибытии на конкурс  -->
    <div class="layout">
        <header><a href="action.php">Меню</a></header>
        <header id="update_arrival_works" title="Подвійне клацання для оновлення відміток у таблиці робіт">Реестрація
            учасників конференції
        </header>
        <?php list_univers_reseption(10); ?>
        <div id="columnAutors"><label>Автори (тільки запрошені)</label><ol id="selectAutors"></ol></div>
        <div id="columnLeaders"><label>Супроводжуючі (усі)</label><ol id="selectLeaders"></ol></div>
    </div>
    <!-- Окончание Отметки о прибытии на конкурс  -->

<?php elseif ($_GET['action'] == "list_autors"): ?>
    <!-- Список авторов работ -->
    <header><a href="action.php">Меню</a></header>
    <header>Список авторів робіт <a href="action.php?action=list_leaders">Список керівників робіт</a></header>
    <header><a href="action.php?action=autor_add&FROM=<?= $FROM; ?>">[ + автор]</a></header>
    <form method="POST" action="lists.php?list=badge_autors">
        <?php list_autors_or_leaders("autors", true, true, false); ?>
        <input type="submit" value="Print">
    </form>
    <!-- Окончание Список авторов работ -->

<?php elseif ($_GET['action'] == "list_leaders"): ?>
    <!-- Список руководителей работ -->
    <header><a href="action.php">Меню</a></header>
    <header><a href="action.php?action=list_autors">Список авторів робіт</a> Список керівників робіт</header>
    <header><a href="action.php?action=leader_add&FROM=<?= $FROM; ?>">[+ керівник]</a></header>
    <form method="POST" action="lists.php?list=badge_leaders">
        <?php list_autors_or_leaders("leaders", true, true, false); ?>
        <input type="submit" value="Print"/>
    </form>
    <!-- Окончание Список руководителей работ -->
<?php elseif ($_GET['action'] == "list_reviewers"): ?>
    <!-- Список Рецензентов работ -->
    <header><a href="action.php">Меню</a></header>
    <header>Список рецензентів робіт</header>
    <header><a href="action.php?action=list_leaders">Керівники</a></header>
    <?php list_autors_or_leaders("leaders", true, true, false,true); ?>
    <!-- Окончание списока рецензентов работ -->


    <!-- Список тезисов по секциям -->
<?php elseif ($_GET['action'] == "tesis"): include "tesis.php";?>
    <!-- Роспеределение секций по аудиториям-->
<?php elseif ($_GET['action'] == "rooms"): include "rooms.php";?>
    <!-- Распределение мест стреди студентов которые приехали на конференцию-->
<?php elseif ($_GET['action'] == "setplace"): include "setplace.php";?>
    <!-- просмотр результата распределения мест -->
<?php elseif ($_GET['action'] == "viewplace"): include "viewplace.php";?>
    <!-- Протокол засідання -->
<?php elseif ($_GET['action'] == "protocol"): include "protocol.php";?>
    <!-- Статистична довідка -->
<?php elseif ($_GET['action'] == "statistic"): include "statistic.php";?>
    <!-- Список на отправку писем-->
<?php elseif ($_GET['action'] == "sentemail"): include "ag_sentmail.php";?>
    <!-- Список на отправку Тестовая разработка -->
<?php elseif ($_GET['action'] == "test"): include "ag_test.php";?>

<?php else: ?>
    <header>Меню</header>
    <!-- ========================================= -->
    <div id="tabs-container">
        <ul class="tabs">
            <li><a href="#">Основна</a></li>
            <li><a href="#" title="Занятие ерундой на рабочем месте хорошо развивает боковое зрение, слух, а также бдительность в целом!" >Наповнення БД</a></li>
            <li><a href="#">І повідомлення</a></li>
            <li><a href="#">IІ повідомлення</a></li>
            <li class="active"><a href="#">Конференція</a></li>
            <li><a href="#">Завершення</a></li>
            <li><a href="#">Службове</a></li>
        </ul>
    </div>
    <!-- Верхнее меню -->
    <div id="nav-container">
        <ul class="nav" id="1" style="display:none;">
            <li><a href="action.php?action=view" title="Таблиця з данними про роботи">Усі роботи</a></li>
            <li><a href="#">Списки</a>
                <ul class="sub">
                    <li><a href="action.php?action=list_autors" title="Редагування данних, видалення">Автори</a></li>
                    <li><a href="action.php?action=list_leaders" title="Редагування данних, видалення">Керівники</a>
                    <li><a href="action.php?action=list_reviewers" title="Редагування данних, видалення">Рецензенти</a>

                    </li>
                </ul>
            </li>
            <li><a href="action.php?action=tesis" title="Таблиця з данними про тезиси">Тезиси</a></li>
            <li><a href="action.php?action=works_update_balls" title="Оновлення рейтингу робіт">Оновити бали рейтингу</a></li>

        </ul>
        <ul class="nav" id="2" style="display:none;">
        <!-- Элементы верхнего меню -->

            <li><a href="action.php?action=autor_add&FROM=<?= $FROM; ?>" title="Внесення в базу даних автора">Данні
                    автора</a></li>
            <li><a href="action.php?action=leader_add&FROM=<?= $FROM; ?>" title="Внесення в базу даних керівника/рецензента">Данні
                    керівника/рецензента</a></li>
            <li><a href="action.php?action=work_add&FROM=<?= $FROM; ?>" title="Внесення в базу даних роботи">Данні
                    роботи</a></li>
            <li><a href="action.php?action=work_link&FROM=<?= $FROM; ?>"
                   title="Встановлення зв'язків робота-&gt;автор, робота-&gt;керівник">Зв'язати роботу</a></li>
            <li><a href="action.php?action=add_all" title="Встановлення зв'язків робота-&gt;автор, робота-&gt;керівник">Ввести
                    усі данні про роботу</a></li>
        </ul>

        <ul class="nav" id="3" style="display:none;">
            <li><a href="action.php?action=univer_invite" title="Друкуєтья у 2-х примірниках (пошта, канцелярія)">Список
                    I</a></li>
            <li><a href="lists.php?list=envelope" title="Формат паперу DL. Перевір налаштування принтеру">Конверти I</a>
            </li>
        </ul>
        <ul class="nav" id="4" style="display:none;">
            <li><a href="action.php?action=invit_section" title="Відмітки про запрошення та розподіл за секціями">Запрошення
                    та секції</a></li>
            <li><a href="#" title="Опрацювати запрощення для жюрі">Запрошення журі</a>
                <ul class="sub">
                    <li><a href="uploadinvitation.php" title="Завантаження сканувань">Завантаження</a></li>
                    <li><a href="action.php?action=invit_leaders" title="Відмітити та роздрукувати">Список відмітити</a>
                    </li>
                </ul>
            </li>
            <li><a href="#"> Друкувати документи</a>
                <ul class="sub">
                    <li><a href="lists.php?list=adress2" title="Друкуєтья у 2-х примірниках (пошта, канцелярія)">Список
                            II</a></li>
                    <li><a href="lists.php?list=envelope2" title="Формат паперу DL. Перевір налаштування принтеру">Конверти
                            II</a></li>
                    <li><a href="lists.php?list=invitation_1"
                           title="Листи друкуються на оффіційному аркуші університету">Листи ректорам</a></li>
                    <li><a href="lists.php?list=invitation_2" title="Формат А4. Список студентів яких запросили">
                            Додаток 1</a></li>
                </ul>
            </li>

        </ul>
        <ul class="nav" id="5" >
            <li><a href="#"> Друкувати до початку конференціх</a>
                <ul class="sub">
                    <li><a href="lists.php?list=ahostel">Список авторів для поселення в гуртожитку</a></li>
                    <li><a href="lists.php?list=lhostel">Список керівників для поселення</a></li>
                    <li><a href="lists.php?list=badge_autors">Бейджики Авторів</a></li>
                    <li><a href="lists.php?list=badge_leaders">Бейджики Керівників</a></li>
                </ul>
            </li>
            <li><a href="programa.php">Скелет программи</a></li>
            <li><a href="action.php?action=rooms">Розподіл секцій за аудиторіями</a></li>
            <li><a href="action.php?action=reception" title="Торжественно клянусь, что замышляю только шалость!">Реєстрація учасників конференції</a></li>
        </ul>
        <ul class="nav" id="6" style="display:none;">
            <li><a href="action.php?action=setplace">Призначення місць</a></li>
            <li><a href="action.php?action=viewplace">Розподіл результат</a></li>
            <li><a href="#">Друкувати</a>
                <ul class="sub">
                    <li><a href="lists.php?list=diploms">Дипломи</a></li>
                    <li><a href="lists.php?list=charters">Грамоты</a></li>
                    <li><a href="lists.php?list=gratitudes">Подяки</a></li>
                </ul>
            </li>
            <li><a href="action.php?action=protocol">Протокол по місцях</a></li>
            <li><a href="action.php?action=statistic">Статистична довідка</a></li>
        </ul>
        <ul class="nav" id="7" style="display:none;">
            <li><a href="invitation.php" title="Сторіка користувачів (попередній перегля)">Сторінка завантежень
                    запрошень</a></li>
            <li><a href="settings.php">Налаштування</a></li>
            <li><a href="log.php" class="special">Журнал дій</a></li>
            <li><a href="#">Розсилка</a>
                <ul class="sub">
                <li><a href="action.php?action=sentemail" class="special" title="Редагувати тектс листа та надіслати запрошення">Електронні запрошення</a></li>
                <li><a href="action.php?action=test"  class="special" title="Тестова сторінка нічого не недасилається">Тестова сторінка</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div style="clear:both"></div>
<?php endif; ?>
<footer><a href="index.php?logoff">Вийти</a></footer>
<div id="test"><?php echo $error_message; ?></div>
<div id="operator">Оператор :<span><?= $_SESSION['usr'] ?></span></div>
<autor>Krupnik&copy;</autor>
</body>
</html>