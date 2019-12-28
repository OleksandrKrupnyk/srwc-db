<?php

use zukr\base\Base;

Base::$session->setFromParam();
?>
<!-- Список руководителей работ -->
<header>
    <a href="action.php">Меню</a>
</header>
<header>
    <a href="action.php?action=author_list">Список авторів</a>
    <div>Список керівників</div>
</header>
<header>
    <a href="action.php?action=leader_add">[+ керівник]</a>
</header>
<form method="POST" action="lists.php?list=badge_leader">
    <?= getListOfObjects('leader', true, true, false); ?>
    <input type="submit" value="Print"/>
</form>