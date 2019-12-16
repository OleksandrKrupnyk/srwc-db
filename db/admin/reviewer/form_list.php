<?php

use zukr\base\Base;

Base::$session->setFromParam();
?>
<!-- Список Рецензентов работ -->
<header><a href="action.php">Меню</a></header>
<header>Список рецензентів робіт</header>
<header><a href="action.php?action=leader_list">Керівники</a></header>
<?= getListOfObjects('leader', true, true, false,true); ?>