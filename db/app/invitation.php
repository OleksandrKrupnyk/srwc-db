<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 20.03.2018
 * Time: 22:22
 */
//header("Content-Type: text/html; charset=utf-8");

require './../admin/config.inc.php';
require './../admin/functions.php';
require '../vendor/autoload.php';

use zukr\base\Base;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\Html;
use zukr\base\Params;
use zukr\base\ReplacerService;
use zukr\leader\LeaderHelper;
use zukr\position\PositionRepository;
use zukr\template\TemplateNameDictionary;
use zukr\template\TemplateService;
use zukr\univer\UniverHelper;
use zukr\workauthor\WorkAuthorRepository;

Base::init();
if (Params::TURN_OFF === Base::$param->INVITATION) {
    Go_page('./');
}

$letter = filter_input(INPUT_GET, 'letter', FILTER_VALIDATE_INT);
$uh = UniverHelper::getInstance();
if (empty($id_u = filter_input(INPUT_GET, 'id_u', FILTER_VALIDATE_INT))):
    $univers = $uh->getInvitedDropdownListWithoutDSTU();
    ?>
    <!DOCTYPE html>
    <html lang="ua">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="../js/jquery.js"></script>
        <link href="../css/userstyle.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="../js/user.js"></script>
        <?php include_once 'analyticstracking.php'; ?>
        <title>Запрошення <?= Base::$app->app_name ?></title>
    </head>
    <body>
    <div class="invitation-block">
        <?php
        $template = (new TemplateService())
            ->getBlockByName(TemplateNameDictionary::INVITATION_PAGE_DESCRIPTION);
        echo (new ReplacerService())->makeReplace($template);
        ?>
    </div>
    <br/>
    <?= Html::select('id_u', null, $univers, ['id' => 'seluniverinv']) ?>
    <a href="#" id="letter1link"><input id="letter1button" type="button" value="Лист до ВНЗ"></a>
    <a href="#" id="letter2link"><input id="letter2button" type="button" value="Додаток 1"></a>
    <a href=<?= APENDEX2 ?>><input id="letter3button" type="button" value="Додаток 2"></a>
    </body>
    </html>
<?php else: ?>
    <!DOCTYPE html>
    <html lang="ua">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../css/print.css" type="text/css" rel="stylesheet"/>
        <title>Запрошення <?= Base::$app->app_name ?></title>
    </head>
    <body>

    <?php
    if ($letter === 1) {
        $univerIds = $uh->getUniversIdWhoSendWork();

        if (empty($univerIds) || !in_array($id_u, $univerIds, true)) {
            Go_page('./');
        }
        $univer = $uh->getUniverById($id_u);
        $leadersList = LeaderHelper::getInstance()->getAllInvitationLeadersByUniverId($univer['id']);
        $leaders = '';
        if (!empty($leadersList)) {
            $positions = (new PositionRepository())->getDropDownList();
            $list = [];
            foreach ($leadersList as $l) {
                $list [] = Html::tag('li', PersonHelper::getFullName($l) . ', ' . $positions[$l['id_pos']]);
            }
            $leaders = '<div id="message2">'
                . '<p>Запрошуємо взяти участь у роботі журі конкурсної комісії конференції представників вашого ВНЗ.</p>'
                . '<ol>' . implode('', $list) . '</ol>'
                . '</div>';
        }
        $datepo = Base::$param->DATEPO;
        $orderpo = Base::$param->ORDERPO;
        $CONTENT = <<<__HTML__
<div class="v_invitation_1">
    <div id="dstuheader" title="Відсканована верхівка офіційного листа"></div>
    <div id="rectory">{$univer['posada']} {$univer['univerrod']}<br>{$univer['rector_r']}</div>
    <div id="message">
    <p>Галузева конкурсна комісія Всеукраїнського конкурсу студентських наукових робіт з 
    галузі &quot;Електротехніка та електромеханіка&quot;  запрошує до участі у підсумковій науково-практичній 
    конференції  авторів кращих робіт </p>
    <p>Список запрошених авторів наукових робіт наведено у Додатку 1.</p>
    <p>Відповідно до &quot;Положення про  проведення Всеукраїнського конкурсу студентських наукових робіт  
    з природничих, технічних та гуманітарних наук&quot; 
    від {$datepo} №{$orderpo} автор наукової роботи, який  не  брав  участі  у  
    підсумковій науково-практичній конференції,  не може бути претендентом на нагородження.</p>
    </div>
    {$leaders}
    <div id="message2">
        <p>Інформація про підсумкову конференцію наведена у Додатку 2.</p>
    </div>
    <div id="podpis_image2" title="Сканований підпис Голови комісії"></div>
</div>
__HTML__;
        echo $CONTENT;
    } else {
        $wah = new WorkAuthorRepository();
        $authors = $wah->getInvitationAuthorsByUniverId($id_u);
        $univer = $uh->getUniverById($id_u);
        $list = [];
        foreach ($authors as $a) {
            $list[] = Html::tag('li', PersonHelper::getFullName($a) . ', (№' . $a['id'] . ')');
        }
        $authorsList = Html::tag('ol', implode('', $list));

        $CONTENT = <<<__HTML__
<div class="v_invitation_2">
    <div id="application1">Додаток 1</div>
    <div id="listsudents_title"><strong>Список студентів</strong></div>
    <div id="univer_title"><em>{$univer['univerrod']}</em></div>
    <div id="message">
        <p>запрошених на підсумкову науково-практичну конференцію 
        Всеукраїнського конкурсу студентських наукових робіт з галузі &quot;Електротехніка та електромеханіка&quot;</p>
        </div>
   {$authorsList}
<div id="podpis_image4" title="Сканований підпис заступника голови"></div><hr>
</div>
__HTML__;
        echo $CONTENT;

    } ?>
    </body>
    </html>
<?php endif; ?>