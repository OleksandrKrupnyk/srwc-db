<!-- Список авторов работ -->
<header><a href="action.php">Меню</a></header>
<header>Список авторів робіт <a href="action.php?action=leader_list">Список керівників робіт</a></header>
<header><a href="action.php?action=autor_add&FROM=<?= $FROM ?>">[ + автор]</a></header>
<form method="POST" action="lists.php?list=badge_autors">
    <?php list_autors_or_leaders('autors', true, true, false); ?>
    <input type="submit" value="Print">
</form>
<!-- Окончание Список авторов работ -->
