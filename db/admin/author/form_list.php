<?php

use zukr\base\Base;

Base::$session->setFromParam();
?>
<!-- Список авторов работ -->
<header><a href="action.php"><i class="icofont-navigation-menu"></i> Меню</a></header>
<header>
    <div>Список авторів</div>
    <a href="action.php?action=leader_list">Список керівників</a>
</header>
<header><a href="action.php?action=author_add">[ + автор]</a></header>
<form class="form" method="POST" action="lists.php?action=badge_authors">
    <?=getListOfObjects(Base::$app->db, 'author', true, true, false, false); ?>
    <input type="submit" value="Print">
</form>
<!-- Окончание Список авторов работ -->
