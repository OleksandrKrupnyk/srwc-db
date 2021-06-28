<?php


use zukr\base\html\Html;

$templateRepository = new \zukr\template\TemplateRepository();
$templates = $templateRepository->findTemplateFromDB();
?>
    <header><a href='action.php'><i class="icofont-navigation-menu"></i> Меню</a></header>
    <header>Шаблони</header>
    <table>
        <tr>
            <th>Назва</th>
            <th>Версія</th>
            <th>Опис</th>
            <th>Активна</th>
            <th>Дії</th>
        </tr>
        <?php foreach ($templates as $template): ?>
            <tr>
                <td><?= $template->name ?></td>
                <td><?= $template->version ?></td>
                <td><?= $template->description ?></td>
                <td><?= $template->published ?></td>
                <td><?= Html::a('Редагувати', '?action=template_edit&id=' . $template->id,
                        [
                            'class' => 'btn'
                        ]) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

