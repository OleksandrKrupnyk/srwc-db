<?php

use zukr\base\Base;
use zukr\univer\UniverRepository;
use zukr\work\WorkHelper;
use zukr\workauthor\WorkAuthorRepository;

$wh = WorkHelper::getInstance();
$wa = (new WorkAuthorRepository())->getAllAuthorsOfWorks();
$univerList = Base::$app->cacheGetOrSet(
    'univer_list',
    (new UniverRepository())->getAllInvitedAsArray(),
    600
);
$viewMenuitem = [
    0 => "<li class='active2'><a href='#'>Всі</a></li>\n",
    1 => "<li class='inactive'><a href='action.php?action=all_view&who=invitation'>Запрошені</a></li>\n",
    2 => "<li class='inactive'><a href='action.php?action=all_view&who=tesis'>З тезами</a></li>\n",
    3 => "<li class='inactive'><a href='action.php?action=all_view&who=arrival'>Прибули</a></li>\n",
    4 => "<li class='inactive'><a href='action.php?action=all_view&who=introduction'>Впроваджені</a></li>\n",
    5 => "<li class='inactive'><a href='action.php?action=all_view&who=public'>З публікацією</a></li>\n",
    6 => "<li class='inactive'><a href='action.php?action=all_view&who=comments'>Примітки</a></li>\n",
    7 => "<li class='inactive'><a href='action.php?action=all_view&who=raiting'>За рейтингом</a></li>\n"
];


if (isset($_GET['who'])) {
    $viewMenuitem[0] = "<li class='inactive'><a href='action.php?action=all_view'>Всі</a></li>" . PHP_EOL;
    switch ($_GET['who']) {
        case 'invitation':
            {
                $allWorks = $wh->getInvitationWorks();
                $viewMenuitem[1] = "<li class='active2'><a href='#'>Запрошені</a></li>\n";
            }
            break;
        case 'tesis':
            {
                $allWorks = $wh->getTesisWorks();
                $viewMenuitem[2] = "<li class='active2'><a href=\"#\">З тезами</a></li>\n";
            }
            break;
        case 'arrival':
            {
                $allWorks = $wh->getArrivalWorks();
                $viewMenuitem[3] = "<li class='active2'><a href='#'>Прибули</a></li>\n";
            }
            break;
        case 'introduction':
            {
                $allWorks = $wh->getIntroductionWorks();
                $viewMenuitem[4] = "<li class='active2'><a href='#'>Впроваджені</a></li>\n";
            }
            break;
        case 'public':
            {
                $allWorks = $wh->getPublicWorks();
                $viewMenuitem[5] = "<li class='active2'><a href='#'>З публікацією</a></li>\n";
            }
            break;
        case 'comments':
            {
                $allWorks = $wh->getCommentsWorks();
                $viewMenuitem[6] = "<li class='active2'><a href='#'>Примітки</a></li>\n";
            }
            break;
        case 'raiting':
            {
                $allWorks = $wh->getOrderByBallsWorks();
                $viewMenuitem[7] = "<li class='active2'><a href='#'>За рейтингом</a></li>\n";
            }
            break;
    }
} else {
    $allWorks = $wh->getAllWorks();
}

$count = count($allWorks);
ob_start(); //включение буфера вывода
?>
<!-- Просмотр  таблици работ -->
<header><a href="action.php">Меню</a></header>
<header>Перегляд бази (<?= $count ?> робіт)</header>
<h1><a href="./invitation.php">=[ Запрошення ]=</a></h1>
<menu class="viewTableMenu">
    <?php vprintf('%s %s %s %s %s %s %s %s', $viewMenuitem); ?>
</menu>


<div id="viewtable">
    <table>
        <tr>
            <th>id<br/>номер</th>
            <th class="title">Назва роботи</th>
            <th class="title">Рецензія</th>
            <th>Керівникі</th>
            <th>Автори
                <номер>(місце)[приїхав]
            </th>
        </tr>

        <?php
        ob_start(); //включение буфера вывода
        if ($_GET['who'] === 'raiting') {
            foreach ($allWorks as $work) {
                $univer = $univerList[$work['id_u']];
                echo print_work_univer($univer['univerfull'], $univer['id'], $univer['univer'], true);
                echo print_work_row($work, true, $_SESSION['id']);
            }
        } else {
            $allWorks = \zukr\base\helpers\ArrayHelper::group($allWorks, 'id_u');
            foreach ($allWorks as $id_u => $works) {
                $univer = $univerList[$id_u];
                echo print_work_univer($univer['univerfull'], $univer['id'], $univer['univer'], true);
                foreach ($works as $work) {
                    echo print_work_row($work, true, $_SESSION['id']);
                }
            }
        }

        ?>
    </table>
</div>
<div id="barUnivers"></div>
<?= ob_get_clean(); // вывод содержимого буффера на экран
?>
<!-- Окончание Просмотр базы -->