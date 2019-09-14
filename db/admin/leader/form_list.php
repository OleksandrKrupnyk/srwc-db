<header>
    <a href="action.php">Меню</a>
</header>
<header>
    <a href="action.php?action=autor_list">Список авторів робіт</a> Список керівників робіт
</header>
<header>
    <a href="action.php?action=leader_add&FROM=<?= $FROM ?>">[+ керівник]</a>
</header>
<form method="POST" action="lists.php?list=badge_leaders">
    <?php list_autors_or_leaders('leaders', true, true, false); ?>
    <input type="submit" value="Print"/>
</form>