<?php


require './../admin/config.inc.php';
require './../admin/functions.php';
require '../vendor/autoload.php';

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\html\Html;

Base::init();
$settings = ArrayHelper::merge($settings, Base::$param->getAllsettingValue());
global $link;
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'file_get') {
        include "ag_file_get_content.php";
    }
}

$setData = static function () use ($link, $settings) {
    $query = '
SELECT w.id,
       w.title,
       w.tesis,
       w.date,
       w.balls,
       w.invitation,
       u.univer,
       s.section,
       (SELECT wa.id_a
        FROM `wa`
        WHERE wa.id_w = w.id
        LIMIT 1)   AS id_a1,
       (SELECT wa.id_a
        FROM `wa`
        WHERE wa.id_w = w.id
        LIMIT 1,2) AS id_a2,
       (SELECT a.place
        FROM `autors` AS a
        WHERE a.id = (
            SELECT wa.id_a
            FROM `wa`
            WHERE wa.id_w = w.id
              AND w.invitation = 1
            LIMIT 1
        )
        LIMIT 1)   AS place1,
       (SELECT a.place
        FROM `autors` AS a
        WHERE a.id = (
            SELECT wa.id_a
            FROM wa
            WHERE wa.id_w = w.id
              AND w.invitation = 1
            LIMIT 1,2
        )
        LIMIT 1)   AS place2,
       (SELECT r.id
        FROM `reviews` as r
       WHERE r.id_w =  w.id
       LIMIT 1) AS reviewer1,
       (SELECT r.id
       FROM `reviews` as r
       WHERE r.id_w =  w.id
       LIMIT 1,2) AS reviewer2,
       (SELECT f.guid
       FROM `files` as f
       WHERE f.id_w = w.id
         AND f.typeoffile = 0
       LIMIT 1) AS file_guid
FROM `works` AS w
         LEFT JOIN `univers` AS u ON w.id_u = u.id
         LEFT JOIN `sections` AS s ON w.id_sec = s.id
         LEFT JOIN `wa` ON wa.id_w = w.id
         LEFT JOIN `autors` AS a ON wa.id_a = a.id
GROUP BY w.id
ORDER BY w.id        
        ';
    $data = [];
    $result = @mysqli_query($link, $query);
    // echo "<pre>{$query}</pre>";
    while ($result !== false &&  $row = mysqli_fetch_array($result)) {
        $s = [];
        $diplomas = [];

        $s['id'] = $row['id'];
        $s['date'] = $row['date'];

        $s['title'] = (
            '1' === Base::$param->SHOW_FILES_LINK // Настройка активна
            &&
            (
                ('D' !== $row['place1'] && $row['place1'] !== null)
                ||
                ('D' !== $row['place2'] && $row['place2'] !== null)
            )
            &&
            $row['file_guid'] !== null // Запись о файле есть в системе
        )
            ? Html::a(
                $row['title'],
                'index.php?action=file_get&guid=' . $row['file_guid'],
                ['title' => $row['title']]
            )
            : $row['title'];


        $s['univer'] = $row['univer'];
        //        $s['invitation'] = ($row['invitation']) ? "<span class='invite2018'>&nbsp; Авторів роботи запрошено до участі у конференції&nbsp;</span>" : '';
        $s['invitation'] = '';

        // Дипломы
        if ('D' !== $row['place1'] && $row['place1'] !== null) {
            $diplomas [] = "<span class='invite2018'>&nbsp;Диплом&nbsp;{$row['place1']}-го&nbsp;ступеня</span>";
        }

        if ('D' !== $row['place2'] && $row['place2'] !== null) {
            $diplomas [] = "<span class='invite2018'>&nbsp;Диплом&nbsp;{$row['place2']}-го&nbsp;ступеня</span>";
        }

        if (count($diplomas) > 0) {
            $s['diploma'] = "<br>" . implode(", ", $diplomas);
        }

        $s['review'] = $row['reviewer1'] !== null ? "<a class='viewReview' href='index.php?action=review_view&id={$row['reviewer1']}'></a>" : '';
        $s['review'] .= $row['reviewer2'] !== null ? "<a class='viewReview' href='index.php?action=review_view&id={$row['reviewer2']}'></a>" : '';
        $s['review'] .= $row['balls'] !== null ? "/{$row['balls']}" : '';
        $s['review'] .= $row['tesis'] == 1 ? "/<br>Отримано" : '';
        $s['section'] = $row['section'];
        $data [] = $s;
    }
    return $data;
};

if (!isset($_GET['action'])) {
    $data = Base::$app->cacheGetOrSet('table', $setData, 30);
}
?>
<!DOCTYPE html >
<html lang="ua">
<head>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="Конкурс,СНР,Електротехніка,електромеханіка,ДДТУ,ЕЛМ,студентських,науових,робіт,конференція"
          name="keywords">
    <meta content="Електротехніка та електромеханіка - реєстр робіт Всеукраїнського конкурсу студентських наукових робіт"
          name="description">
    <?php include_once 'analyticstracking.php'; ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="../css/userstyle.min.css" type="text/css" rel="stylesheet">
    <title><?= Base::$app->app_name ?></title>
</head>
<body>
<?php if (isset($_GET['action'])): ?>
    <?php
    if ($_GET['action'] === 'review_view') {
        include "ag_form_view_review.php";
    }?>
<?php else: ?>
    <h1>Реєстр робіт Всеукраїнського конкурсу студентських наукових робіт
        <br>&quot;Електротехніка та електромеханіка&quot; <?= Base::$param->NYEARS ?> н.р.</h1>
    <h4>Данні реєстрів робіт
        &nbsp;&nbsp;&nbsp;&nbsp;<a href='../../db2013'>2013-2014</a>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href='../../db2014'>2014-2015</a>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href='../../db2015'>2015-2016</a>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href='../../db2016'>2016-2017</a>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href='../../db2017'>2017-2018</a>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href='../../db2018'>2018-2019</a>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href='../../db2019'>2019-2020</a>
        &nbsp;&nbsp;&nbsp;&nbsp;навчальних років.
    </h4>

<!--    <h4><a href='http://elm-dstu-edu.org.ua/konkurs/index.php/digest/32-zbirnik-tez-2018'-->
<!--           title='Збірник тез доповідей 2017-2018 н.р.'>Збірник тез доповідей 2018</a>&#8658;</h4>-->
    <?php if ('1' === $settings['SHOW_PROGRAMA']): ?>
        <h4><a href='./programa.php'>Макет програми конференції&#8658;</a></h4>
    <?php endif; ?>

    <?php if ('1' === $settings['INVITATION']): ?>
        <h4><a href='./invitation.php'>Сторінка завантажень запрошень учасників конференції</a>&#8658;</h4>
    <?php endif; ?>

    <?php
    //echo "<h4><a href='http://elm-dstu-edu.org.ua/db/document3.pdf' title='МАКЕТ Збірника тез доповідей 2017-2018 н.р.'>Збірник тез доповідей 2018 [МАКЕТ від 13.04.18] &#8658;</a></h4>";
    //echo "<p>Просимо авторів тез первірити наявність публікації в МАКЕТІ збірника. У випадку, якшо вами були надіслані тези у форматі ТЕХ, але вони відсутні в МАКЕТІ збірника просимо повдомити про це на поштову скриньку <span class='invite2018'><a href=\"mailto:conkstudrabot@gmail.com\">conkstudrabot@gmail.com</span></a> до 14 квітня 2018р вказавши в тексті листа номер роботи та дату відправлення електронного листа з тезами.</p>";
    ?>
    <?php if ('1' === $settings['SHOW_DB_TABLE']): ?>
        <h3>Роботи авторів та відомості про їх рецензування, а також наявність тез доповідей. Розподіл за секціями.</h3>
        <table style="border-collapse: collapse">
            <thead>
            <tr>
                <th>&numero;</th>
                <th>Назва роботи</th>
                <th>Рецензії/<br>Бали/<br>Тези</th>
                <th>Секція*</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $d): ?>
                <tr>
                    <td class='numero'><?= $d['id'] ?></td>
                    <td title="Останні зміни:<?= $d['date'] ?>" class='title'><?= $d['title'] ?><br><?= $d['univer'] ?><?= $d['invitation'] ?><?= $d['diploma'] ?? '' ?></td>
                    <td class='review'><?= $d['review'] ?></td>
                    <td class='section'><?= $d['section'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" style="border-color: white">* - Розподілення робіт по секціях може бути змінено після їх
                    рецензування.
                </td>
            </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <h1>Конкурс СНР <?= Base::$param->NYEARS ?> н.р. завершено. Результати переміщено в архів.</h1>
        <!--
        Закоментировав предидущую строку раскоментируй следующую (2018.06.13)
        <h1>Вибачте Реєст знаходиться в обробці. Завітайте на нашу сторінку пізніше.</h1>
        <h2>(Зараз. Роботи отримують рецензію. Після цього процесу та підбиття підсумків буде оприлюднено рейтинг робіт, а також інформація про роботи автори яких запрошено на підсумкову конференцію.)</h2>
        <p>Після офіційної розсилки запрошень до ВНЗ учасників конкурсу, а також після розислки запрошень на електронні поштові скриньки на цій сторінці можна буде завантажити відскановані копії запршень для оформлення відряджень.</p>
        -->
    <?php endif; ?>
<?php endif; ?>
<div class="autor">Krupnik&copy;&nbsp;</div>
</body>
</html>
