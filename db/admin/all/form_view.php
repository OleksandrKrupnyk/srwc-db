<?php

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\univer\UniverHelper;
use zukr\work\WorkHelper;

$wh = WorkHelper::getInstance();
//$wa = (new WorkAuthorRepository())->getAllAuthorsOfWorks();
$uh = UniverHelper::getInstance();
$univerList = $uh->getAllUniversFromDB();
$userLogin = Base::$user;
$session = Base::$session;
$session->setFromParam();
$viewMenuitem = [
    0 => '<li class="active2"><a href="#">Всі</a></li>',
    1 => '<li class="inactive"><a href="action.php?action=all_view&who=invitation">Запрошені</a></li>',
    2 => '<li class="inactive"><a href="action.php?action=all_view&who=tesis">З тезами</a></li>',
    3 => '<li class="inactive"><a href="action.php?action=all_view&who=arrival">Прибули</a></li>',
    4 => '<li class="inactive"><a href="action.php?action=all_view&who=introduction">Впроваджені</a></li>',
    5 => '<li class="inactive"><a href="action.php?action=all_view&who=public">З публікацією</a></li>',
    6 => '<li class="inactive"><a href="action.php?action=all_view&who=comments">Примітки</a></li>',
    7 => '<li class="inactive"><a href="action.php?action=all_view&who=raiting">За рейтингом</a></li>'
];


if (isset($_GET['who'])) {
    $viewMenuitem[0] = '<li class="inactive"><a href="action.php?action=all_view">Всі</a></li>' . PHP_EOL;
    switch ($_GET['who']) {
        case 'invitation':
            {
                $allWorks = $wh->getInvitationWorks();
                $viewMenuitem[1] = '<li class="active2"><a href="#">Запрошені</a></li>';
            }
            break;
        case 'tesis':
            {
                $allWorks = $wh->getTesisWorks();
                $viewMenuitem[2] = '<li class="active2"><a href="#">З тезами</a></li>';
            }
            break;
        case 'arrival':
            {
                $allWorks = $wh->getArrivalWorks();
                $viewMenuitem[3] = '<li class="active2"><a href="#">Прибули</a></li>';
            }
            break;
        case 'introduction':
            {
                $allWorks = $wh->getIntroductionWorks();
                $viewMenuitem[4] = '<li class="active2"><a href="#">Впроваджені</a></li>';
            }
            break;
        case 'public':
            {
                $allWorks = $wh->getPublicWorks();
                $viewMenuitem[5] = '<li class="active2"><a href="#">З публікацією</a></li>';
            }
            break;
        case 'comments':
            {
                $allWorks = $wh->getCommentsWorks();
                $viewMenuitem[6] = '<li class="active2"><a href="#">Примітки</a></li>';
            }
            break;
        case 'raiting':
            {
                $allWorks = $wh->getOrderByBallsWorks();
                $viewMenuitem[7] = '<li class="active2"><a href=\'#\'>За рейтингом</a></li>';
            }
            break;
    }
} else {
    $allWorks = $wh->getAllWorks();
}
$who = filter_input(INPUT_GET, 'who', FILTER_SANITIZE_STRING) ?? '';
$count = count($allWorks);
?>
<!-- Просмотр  таблици работ -->
<header><a href="action.php">Меню</a></header>
<header>Перегляд бази (<?= $count ?> робіт)</header>
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
                &lt;номер&gt;(місце)[приїхав]
            </th>
        </tr>

        <?php
        if ($who === 'raiting') {
            foreach ($allWorks as $work) {
                $univer = $univerList[$work['id_u']];
                echo print_work_univer($univer['univerfull'], $univer['id'], $univer['univer'])
                    . print_work_row($work, $userLogin);
            }
        } else {
            $allWorks = ArrayHelper::group($allWorks, 'id_u');
            foreach ($allWorks as $id_u => $works) {
                $univer = $univerList[$id_u];
                echo print_work_univer($univer['univerfull'], $univer['id'], $univer['univer']);
                foreach ($works as $work) {
                    echo print_work_row($work, $userLogin);
                }
            }
        }

        ?>
    </table>
</div>
<div id="barUnivers"></div>
<!-- Окончание Просмотр базы -->