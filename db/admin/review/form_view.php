<?php

use zukr\base\Base;
use zukr\base\Record;
use zukr\review\ReviewHelper;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    Base::$session->setFlash('recordSaveMsg', 'Не вказано id роботи');
    Base::$session->setFlash('recordSaveType', 'error');
    Go_page('error');
}
$rh = ReviewHelper::getInstance();
$review = $rh->getReviewRepository()->getById($id);
$work = $rh->getWorksRepository()->getById($review['id_w']);
$conclusion = (int)$review['conclusion'] === Record::KEY_OFF
    ? 'Не рекомендується '
    : 'Рекомендується';
?>
<!--Просмотр рецензии -->
<header><a href="action.php?action=all_view#id_w<?= $review['id_w'] ?>">Усі роботи</a></header>
<header>Перегляд рецензії</header>
<h1><?= $work['title'] ?></h1>
<table>
    <tr>
        <th>#</th>
        <th class="w-100">Показник</th>
        <th>Бали</th>
    </tr>
    <?php
    $i = 1;
    $sum = 0;
    foreach ($rh->getQualities() as $key => $item):
        $value = $review[$key] ?? $item['max'];
        $sum += $value;
        ?>
        <tr>
            <td><?= ($i++) ?></td>
            <td><?= $item['title'] ?><?= $item['description'] ?? '' ?></td>
            <td><?= $value ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td>12</td>
        <td>Сума</td>
        <td><?= $sum ?></td>
    </tr>
</table>
<fieldset>
    <legend>Зауваження та недоліки</legend>
    <p><?= htmlspecialchars($review['defects']) ?></p>
</fieldset>
<p>Загальний висновок : <?= $conclusion ?> для захисту на науково-практичній конференції</p>