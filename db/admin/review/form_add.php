<?php

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\Html;
use zukr\review\ReviewHelper;
use zukr\work\WorkHelper;
use zukr\work\WorkRepository;

$id_w = filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT);
$id_u = filter_input(INPUT_GET, 'id_u', FILTER_VALIDATE_INT);
$rh = ReviewHelper::getInstance();

if (!$id_w || !$id_u) {
    Base::$session->setFlash('recordSaveMsg', 'Не вказана робота або ВНЗ');
    Base::$session->setFlash('recordSaveType', 'warning');
    Go_page('action.php?action=all_view');
}
$countOfWorks = $rh->getReviewRepository()->getCountOfReviewByWorkId($id_w);
if ($countOfWorks > 1) {
    Base::$session->setFlash('recordSaveMsg', 'Для роботи достатньо рецензій');
    Base::$session->setFlash('recordSaveType', 'warning');
    Go_page('action.php?action=all_view');
}

$work = (new WorkRepository())->getById($id_w);
$wh = WorkHelper::getInstance();

if (Base::$user->getUser()->isAdmin()) {
    $listReviewers = $rh->getListReviewers($id_w, $id_u);
    [$items, $options] = $rh->getWorksWithoutFullReviews();
} elseif ((int)Base::$param->DENNY_EDIT_REVIEW === Base::KEY_OFF) {
    if (Base::$user->getUser()->isReview()) {
        $userData = Base::$user->getUser()->getProfile();
        if ($userData['id_u'] === $work['id_u']) {
            Base::$session->setFlash('recordSaveMsg', 'Ви представник ВНЗ з якого надіслана робота');
            Base::$session->setFlash('recordSaveType', 'error');
            Go_page('action.php?action=all_view');
        }
        $userFullName =PersonHelper::getFullName($userData);
    }
} else {
    Base::$session->setFlash('recordSaveMsg', 'Ви не маєте права на рецензію роботи');
    Base::$session->setFlash('recordSaveType', 'error');
    Go_page('action.php?action=all_view');
}
?>
<!--Добавление рецензии -->
<header><a href="action.php?action=all_view#id_w<?= $id_w ?>">Усі роботи</a></header>
<header>Додавання рецензії</header>
<form class="addreviewForm form" method="post" action="action.php" id="review-form">
    <?php if (Base::$user->getUser()->isAdmin()): ?>
        <label>Оберіть роботу :</label>
        <?= Html::select('Review[id_w]', $id_w, $items,
            ArrayHelper::merge($options, [
                'id' => 'review-id_w',
                'class' => 'w-100',
                'required' => true
            ])
        ) ?>
    <?php else: ?>
        <label>Робота :</label>
        <h4><?= $work['title'] ?></h4>
        <input type="hidden" name="Review[id_w]" value="<?= $id_w ?>">
    <?php endif; ?>
    <fieldset name="descriptionWorks" id="descriptionWorks">
        <legend>Данні з роботи(виключно для рецензента)</legend>
        <p><?= $rh->getWorkDescription($work) ?></p></fieldset>
    <table>
        <tr>
            <th>#</th>
            <th>Показник</th>
            <th colspan="2">Бали</th>
        </tr>
        <?php
        $i = 1;
        $summa = 0;
        foreach ($rh->getQualities() as $key => $item):
            $value = $review[$key] ?? $item['max'];
            $summa += $value;
            ?>
            <tr>
                <td><?= ($i++) ?></td>
                <td class="w-100"><?= $item['title'] ?><?= $item['description'] ?? '' ?></td>
                <td><input type="range" name="Review[<?= $key ?>]" min="0" max="<?= $item['max'] ?>" step="1"
                           title="max <?= $item['max'] ?>" value="<?= $value ?>"></td>
                <td>
                    <output class="balls"><?= $value ?></output>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td><?= ($i++) ?></td>
            <td><strong>Сума</strong></td>
            <td colspan="2">
                <output name="summa" class="balls summ"><?= $summa ?></output>
            </td>
        </tr>
    </table>
    <fieldset>
        <legend><label for="review-defects">Зауваження та недоліки</label></legend>
        <textarea id='review-defects' name="Review[defects]" rows="4" cols="40" maxlength="4096" class="w-100"
                  placeholder="Недоліки роботи по пунктах в яких низька кількість балів або об’єктивна оцінка. В середньому 2-4 речення."></textarea>
    </fieldset>
    <br>
    <label for="review-conclusion">Висновок рецензента :</label>
    <?= Html::select('Review[conclusion]', '0',
        ['1' => 'Рекомендувати', '0' => 'Не рекомендувати']
        , ['id' => 'review-conclusion', 'required' => true]) ?>
    <label>до участі у підсумковій конференції.</label><br>
    <label>Рецензент :</label>
    <?php if (Base::$user->getUser()->isAdmin()): ?>
        <?= Html::select('Review[review1]', null, $listReviewers, [
            'required' => true, 'class' => 'w-100', 'id' => 'review-review1'
        ]) ?>
    <?php else:
        ?>
        <strong><?= $userFullName ?></strong>
        <input type="hidden" value="<?= $userData['id'] ?>" name="Review[review1]">
    <?php endif; ?>
    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="button" value="Повернутися" name="return"
           onclick="window.location='action.php?action=all_view#id_w'+<?= $_GET['id_w'] ?>">
    <input type="hidden" name="action" value="review_add">
    <input type="hidden" name="id_u" value="<?= $id_u ?>">
</form>
<?= $rh->registerJS('review.js') ?>
