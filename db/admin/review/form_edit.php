<?php

use zukr\base\Base;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\Html;
use zukr\leader\LeaderHelper;
use zukr\review\ReviewHelper;

/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 10.11.17
 * Time: 22:45
 */
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    Base::$session->setFlash('recordSaveMsg', 'Не вказано id роботи');
    Base::$session->setFlash('recordSaveType', 'error');
    Go_page('error');
}
$rh = ReviewHelper::getInstance();
$review = $rh->getReviewRepository()->getById($id);
$work = $rh->getWorksRepository()->getById($review['id_w']);

if (Base::$user->getUser()->isAdmin()) {
    $listReviewers = $rh->getListEditableReviewers($review['id_w'], $work['id_u'], $review['review1']);
    [$items, $options] = $rh->getWorksWithoutFullReviews();
} elseif ((int)Base::$param->DENNY_EDIT_REVIEW === Base::KEY_OFF) {
    if (Base::$user->getUser()->isReview()) {
        $userData = Base::$user->getUser()->getProfile();
        if ($userData['id_u'] === $work['id_u'] || $review['review1'] !== $userData['id']) {
            Go_page('action.php?' . http_build_query(['action' => 'review_view', 'id' => $review['id']]));
        }
        $lh = LeaderHelper::getInstance();
        $userFullName = PersonHelper::getFullName($userData);
    }
} else {
    Base::$session->setFlash('recordSaveMsg', 'Ви не маєте права на рецензію роботи');
    Base::$session->setFlash('recordSaveType', 'error');
    Go_page('action.php?action=all_view');
}
$isAdmin = Base::$user->getUser()->isAdmin();
Base::$param->DENNY_EDIT_REVIEW;
?>
<!--Редактирование рецензии -->
<header><a href="action.php?action=all_view#id_w<?= $review['id_w'] ?>">Усі роботи</a></header>
<header>Редагування рецензії</header>

<form class="addreviewForm form" method="post" action="action.php" id="review-form">
    <h1><?= $work['title'] ?></h1>
    <fieldset name="descriptionWorks" id="descriptionWorks">
        <legend>Данні з роботи(виключно для рецензента)</legend>
        <p><?= $rh->getWorkDescription($work) ?></p>
    </fieldset>
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
            <td>12</td>
            <td><strong>Сума</strong></td>
            <td colspan="2">
                <output name="summa" class="balls summ"><?= $summa ?></output>
            </td>
        </tr>
    </table>

    <fieldset>
        <legend><label for="review-defects">Зауваження та недоліки</label></legend>
        <textarea id='review-defects' name="Review[defects]" rows="4" cols="40" maxlength="4096" class="w-100"
                  placeholder="Недлоліки роботи по пунктах в яких низька кількість балів або об’єктина оцінка. В середньому 2-4 речення."><?= htmlspecialchars($review['defects']); ?></textarea>
    </fieldset>
    <br>
    <label for="review-conclusion">Висновок рецензента :</label>
    <?= Html::select('Review[conclusion]', $review['conclusion'],
        ['1' => 'Рекомендувати', '0' => 'Не рекомендувати']
        , ['id' => 'review-conclusion', 'required' => true]) ?><label>до участі у підсумковій конференції.</label><br>
    <label>Рецензент :</label>
    <br>
    <?php if (Base::$user->getUser()->isAdmin()): ?>
        <?= Html::select('Review[review1]', $review['review1'], $listReviewers, [
            'required' => true, 'class' => 'w-100', 'id' => 'review-review1'
        ]) ?>
    <?php else:
        ?>
        <strong><?= $userFullName ?></strong>
        <input type="hidden" value="<?= $userData['id'] ?>" name="Review[review1]">
    <?php endif; ?>
    <input type="hidden" value="<?= $review['id'] ?>" name="Review[id]">
    <input type="hidden" value="<?= $review['id'] ?>" name="id">
    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="button" value="Повернутися"
           onclick="window.location='action.php?action=all_view#id_w'+<?= $review['id_w'] ?>">
    <input type="hidden" name="action" value="review_edit">
</form>
<?= $rh->registerJS('review.js') ?>
