<?php

use zukr\base\Base;

Base::$session->setFromParam();
?>
<!-- Список руководителей работ -->
<header>
    <a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a>
</header>
<header>
    <a href="action.php?action=author_list">Список авторів</a>
    <div>Список керівників</div>
</header>
<header>
    <a href="action.php?action=leader_add">[+ керівник]</a>
</header>
<form class="form" method="POST" action="lists.php?action=badge_leaders">
    <?= getListOfObjects(Base::$app->db, 'leader', true, true, false, false); ?>
    <input type="submit" value="Print"/>
</form>