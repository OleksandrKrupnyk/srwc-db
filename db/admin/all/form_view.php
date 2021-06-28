<?php

use zukr\author\AuthorHelper;
use zukr\base\AuthInterface;
use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\Html;
use zukr\base\html\HtmlHelper;
use zukr\base\LoginUser;
use zukr\file\FileHelper;
use zukr\leader\LeaderHelper;
use zukr\review\ReviewHelper;
use zukr\section\SectionHelper;
use zukr\univer\UniverHelper;
use zukr\work\WorkHelper;

/**
 * Выводит заголовок  "название университета" в таблице просмотра данных о работах
 *
 * @param string $univer_title
 * @param int $id_u
 * @param string $univer
 *
 * @return string
 */
function print_work_univer($univer_title, $id_u, $univer)
{
    $FROM = $_SESSION['from'] ?? '';
    return '
    <tr>
    <td colspan="5" class="univerTitle">
    <div id=id_u' . $id_u . ' style="display:inline;margin-right:5px">' . $univer . '</div>
    <a href="action.php?action=univer_edit&id_u=' . $id_u . '&FROM=' . $FROM . '" title="Редагувати данні університету">' . $univer_title . '</a>
    </td>
    </tr>';
}

/**
 * Выводит рядок в таблице просмотра данных о работе
 *
 * @param array $work
 * @param LoginUser $userLogin
 *
 * @return string
 */
function print_work_row(array $work, LoginUser $userLogin)
{

    $sh = SectionHelper::getInstance();
    $sections = $sh->getAllSections();
    $section = $sections[$work['id_sec']]['section'] ?? '';

    $ah = AuthorHelper::getInstance();
    $autors = $ah->getAutorsByWorkId($work['id']);

    $lh = LeaderHelper::getInstance();
    $leaders = $lh->getLeadersByWorkId($work['id']);

    $fh = FileHelper::getInstance();
    $filesOfWork = $fh->getFilesOneWork($work['id']);
    $rh = ReviewHelper::getInstance();
    /**
     * @var AuthInterface $user
     */
    $user = $userLogin->getUser();

    $title = '<a href=action.php?action=work_edit&id_w=' . $work['id'] . ' title="Редагувати роботу" class="">';
    $title .= (int)$work['arrival'] === Base::KEY_ON
        ? $work['title'] . '&nbsp;[&radic;]&nbsp;'
        : $work['title'];
    $title .= '</a>';

    $invitateClassWork = (int)$work['invitation'] === Base::KEY_ON
        ? 'invitateWork'
        : '';

    $tesis = (int)$work['tesis'] === Base::KEY_OFF ? '' : '<strong>З тезами</strong>';

    $list_leaders = WorkHelper::leaderList($leaders, false);

    $list_autors = WorkHelper::authorList($autors, true, true);

    $date = $work['date'];

    //если установлено показывать ссылки
    $link_add_review = (
        (
            ($user->isReview() && (int)Base::$param->DENNY_EDIT_REVIEW === Base::KEY_OFF)
            ||
            $user->isAdmin()
        )
        && (int)$rh->getCountOfReviewByWorkId($work['id']) < 2
    )
        ? '<a href="action.php?action=review_add&id_w=' . $work['id'] . '&id_u=' . $work['id_u'] . '">додати рецензію</a>'
        : '';

    $reviewsData = list_reviews_for_one_work(
        $work['id'],
        ($user->isReview() && (int)Base::$param->DENNY_EDIT_REVIEW === Base::KEY_OFF) || $user->isAdmin(),
        $user->isAdmin(),
        $userLogin->getId()
    );


    $introduction = !empty($work['introduction'])
        ? '<br/><strong>Впровадження</strong>:' . $work['introduction'] . PHP_EOL
        : '';
    $public = !empty($work['public'])
        ? "<br/><strong>Публікації</strong> :{$work['public']}" . PHP_EOL
        : '';
    $delete_work = Base::$user->getUser()->isAdmin()
        ? Html::a(
            '<i class="icofont-ui-delete"></i>',
            'action.php?' . http_build_query(['action' => 'work_delete', 'id_w' => $work['id']]),
            ['title' => "Видалити роботу з реєстру (Зникнуть зв\'язки, автори та керівникі будуть у базі)"])
        . PHP_EOL
        : '' . PHP_EOL;
    $files = HtmlHelper::listFiles($filesOfWork);
    $rowspan = '3';
    return <<<ROWTABLE
<tr class="{$invitateClassWork}">
            <td rowspan="{$rowspan}" class="workID"><div id="id_w{$work['id']}">{$work['id']}</div></td>
            <td colspan="4" class="title" title="Останні зміни :$date">
            <!-- Действия над работой -->
            {$title}&nbsp;&nbsp;<a href="action.php?action=work_link&id_w={$work['id']}" title="Зв'язати з роботою керівника/автора">&laquo;</a>&nbsp;&nbsp;&nbsp;&nbsp;{$delete_work} 
            </td>
</tr>        
<tr   class="{$invitateClassWork}">
            <td class="tdInfo">
                <strong>Секція:</strong>{$section}
                <br/><strong>Шифр</strong>: {$work['motto']} {$tesis}
                $public
                $introduction $link_add_review
            </td>
            <td>$reviewsData</td>
            <td rowspan="1">$list_leaders</td>
            <td rowspan="1">$list_autors</td>
    </tr>
    <tr class="{$invitateClassWork}">
        <td colspan="1">$files</td>
        <td colspan="4" title="Коментарі та зауваження" >{$work['comments']}</td>
    </tr>
ROWTABLE;
}

/**
 * @param int $id_w ІД запису роботи
 * @param bool $href TRUE if you need to show link for edit
 * @param bool $isAdmin
 * @param int $loginId ІД запису користувача
 * @return string Numeric list of reviews with links
 */
function list_reviews_for_one_work($id_w, bool $href = false, bool $isAdmin = false, int $loginId = 0)
{
    $rh = ReviewHelper::getInstance();
    $reviews = $rh->getDecisionIndexedByWorkId()[$id_w] ?? [];

    $fullSum = 0;
    $conclusions = '';
    $str = '<ol>';
    foreach ($reviews as $review) {
        $fullSum += $review['sumball'];
        $item = '';
        $fio = PersonHelper::getFullName($review);
        if ($href) {
            $item .= Html::a(
                "<i class='icofont-ui-edit'></i>:[{$review['sumball']}]",
                'action.php?' . http_build_query(['action' => 'review_edit', 'id' => $review['id']]),
                ['title' => 'Ред. Рец.:' . $fio]);
            if (isAuthorOfReview($loginId, $review) || $isAdmin) {
                $item .= Html::a('<i class="icofont-ui-delete"></i>',
                    'action.php?' . http_build_query(['action' => 'review_delete', 'id' => $review['id'], 'id_w' => $review['id_w']]),
                    ['title' => 'Видалити рецензію']);
            }
        } else {
            $item .= Html::a(
                'Реценз.',
                'action.php?' . http_build_query(['action' => 'review_view', 'id' => $review['id']]),
                ['title' => '[' . $review['sumball'] . ']']);
        }
        $conclusions .= (int)$review['conclusion'] === Base::KEY_ON ? 'ТАК&nbsp;' : 'НІ&nbsp;';

        $str .= '<li>' . $item . '</li>';
    }

    $str .= '</ol>';

    $str .= "<p>&nbsp;&nbsp;&nbsp;<strong>&sum;:{$fullSum}</strong>&nbsp;{$conclusions}</p>";
    return $str;
}

/**
 * @param int $loginId
 * @param array $review
 * @return bool
 */
function isAuthorOfReview(int $loginId, array $review): bool
{
    return (int)$loginId === (int)$review['id_tzmember'];
}

$wh = WorkHelper::getInstance();
$uh = UniverHelper::getInstance();
$univerList = $uh->getUnivers();
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
<!-- Просмотр  таблицы работ -->
<header><a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a></header>
<header>Перегляд бази (<?= $count ?> робіт)</header>
<menu class="viewTableMenu"><?= vsprintf('%s %s %s %s %s %s %s %s', $viewMenuitem); ?></menu>
<div id="viewtable">
    <table>
        <tr>
            <th>id<br/>номер</th>
            <th class="title">Назва роботи</th>
            <th class="title">Рецензія</th>
            <th>Керівники</th>
            <th>Автори
                &lt;номер&gt;(місце)[приїхав]
            </th>
        </tr>

        <?php
        $listUniversLinks = [];
        if ($who === 'raiting') {
            foreach ($allWorks as $work) {
                $univer = $univerList[$work['id_u']];
                echo print_work_univer($univer['univerfull'], $univer['id'], $univer['univer'])
                    . print_work_row($work, $userLogin);
                $listUniversLinks[$univer['id']] = $univer['univer'];
            }
        } else {
            $allWorks = ArrayHelper::group($allWorks, 'id_u');
            foreach ($allWorks as $id_u => $works) {
                $univer = $univerList[$id_u];
                echo print_work_univer($univer['univerfull'], $univer['id'], $univer['univer']);
                foreach ($works as $work) {
                    echo print_work_row($work, $userLogin);
                }
                $listUniversLinks[$univer['id']] = $univer['univer'];
            }
        }
        $barUnivers = '';
        foreach ($listUniversLinks as $id => $u) {
            $barUnivers .= "<li><a href='#id_u{$id}'>{$u}</a></li>";
        }
        ?>
    </table>
</div>
<div class="barUnivers">
    <ol><?= $barUnivers ?></ol>
</div>
<!-- Окончание Просмотр базы -->