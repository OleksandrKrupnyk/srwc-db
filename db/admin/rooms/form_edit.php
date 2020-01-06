<?php
/** ВИбір аудиторії
 * Created by PhpStorm.
 * User: sasha
 * Date: 21.03.2018
 * Time: 0:38
 */

use zukr\base\html\Html;
use zukr\section\SectionHelper;
use zukr\section\SectionRepository;

$sections = (new SectionRepository())->getSectionAndCountRooms();
?>
<!-- Роспеределение секций по аудиториям-->
<header><a href="action.php">Меню</a></header>
<header>Аудиторії</header>
<table id='tableSelectRoom'>
    <tr>
        <th>Шифр<br/>секції</th>
        <th>Назва секції</th>
        <th>Аудиторія</th>
        <th>Робіт</th>
    </tr>
    <tbody>
    <?php
    foreach ($sections as $s) {
        echo "<tr data-key='{$s['id']}'>
<td>{$s['id']}</td><td>{$s['section']}</td><td>" . Html::select('room', $s['room'], ['7-43' => '7-43', '7-53' => '7-53', '7-54' => '7-54']) . "</td><td>{$s['count']}</td></tr>";
    }
    ?>
    </tbody>
</table>
<?= SectionHelper::getInstance()->registerJS() ?>
<!-- Окончание распределения секций по аудиториям-->