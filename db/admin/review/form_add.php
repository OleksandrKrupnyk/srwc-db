<?php

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\html\Html;
use zukr\review\ReviewHelper;
use zukr\work\WorkHelper;
use zukr\work\WorkRepository;

$id_w = filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT);

if (!$id_w) {
    Base::$session->setFlash('recordSaveMsg', 'Не вказана робота');
    Base::$session->setFlash('recordSaveType', 'info');
    Go_page('action.php?action=all_view');
}
$work = (new WorkRepository())->getById($id_w);
$rh = ReviewHelper::getInstance();
$wh = WorkHelper::getInstance();
[$items, $options] = $rh->getWorksWithoutFullReviews();
?>
<!--Добавление рецензии -->
<header><a href="action.php?action=all_view#id_w<?= $id_w ?>">Усі роботи</a></header>
<header>Додавання рецензії</header>
<form class="addreviewForm" method="post" action="action.php" id="review-add-form">
    <label>Оберіть роботу :</label>
    <?= Html::select('Review[id_w]', $id_w, $items,
        ArrayHelper::merge($options, [
            'id' => 'review-id_w',
            'class' => 'w-100',
            'required' => true
        ])
    ) ?>
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
        foreach ($rh->getQualities() as $key => $item):
            $value = $review[$key] ?? $item['max'];
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
                <output name="summa" class="balls summ">120</output>
            </td>
        </tr>
    </table>
    <fieldset>
        <legend><label for="review-defects">Зауваження та недоліки</label></legend>
        <textarea id='review-defects' name="Review[defects]" rows="4" cols="40" maxlength="4096" class="w-100"
                  placeholder="Недлоліки роботи по пунктах в яких низька кількість балів або об’єктина оцінка. В середньому 2-4 речення."></textarea>
    </fieldset>
    <br>
    <label for="review-conclusion">Висновок рецензента :</label>
    <?= Html::select('Review[conclusion]', '0',
        ['1' => 'Рекомендувати', '0' => 'Не рекомендувати']
        , ['id' => 'review-conclusion', 'required' => true]) ?>
    <label>до участі у підсумковій конференції.</label><br>
    <label>Рецензент :</label><?php cbo_reviewers_list($_GET['id_u'], $_GET['id_w'], $id = -1); ?>
    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="button" value="Повернутися" name="return"
           onclick="window.location='action.php?action=all_view#id_w'+<?= $_GET['id_w'] ?>">
    <input type="hidden" name="action" value="review_add">
</form>
<?= $rh->registerJS() ?>
