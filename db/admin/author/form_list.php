<?php

use zukr\base\Base;

Base::$session->setFromParam();
?>
<!-- Список авторов работ -->
<header><a href="action.php">Меню</a></header>
<header>
    <div>Список авторів</div>
    <a href="action.php?action=leader_list">Список керівників</a>
</header>
<header><a href="action.php?action=author_add">[ + автор]</a></header>
<form method="POST" action="lists.php?list=badge_autors">
    <?=getListOfObjects('author', true, true, false); ?>
    <input type="submit" value="Print">
</form>
<!-- Окончание Список авторов работ -->
