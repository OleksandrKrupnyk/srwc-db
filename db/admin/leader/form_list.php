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
<header style="width:fit-content;">
    <a href="action.php?action=leader_add" title="Додати керівника"><i class="icofont-plus"><i class="icofont-teacher"></i></i></a>
</header>
<form class="form" method="POST" action="lists.php?action=badge_leaders">
    <?= getListOfObjects(Base::$app->db, 'leader', true, true, false, false); ?>
    <input type="submit" value="Print"/>
</form>