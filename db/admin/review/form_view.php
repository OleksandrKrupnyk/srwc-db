<?php
$query = "SELECT `reviews`.*, `works`.`title`,
          SUM(`actual`+`original`+`methods`+`theoretical`+`practical`+`literature`+`selfcontained`+`design`+`publication`+`government`+`tendentious`) AS sumball
          FROM `reviews`
          JOIN `works` ON `works`.`id` = `reviews`.`id_w` 
          WHERE `reviews`.`id` ='{$_GET['id']}'";
global $link;
$result = mysqli_query($link, $query) or die("Invalid query in function list_reviews_for_one_work : " . mysqli_error($link));
$row = mysqli_fetch_array($result);
?>
<!--Просмотр рецензии -->
<header><a href="action.php?action=all_view#id_w<?= $row['id_w'] ?>">Усі роботи</a></header>
<header>Перегляд рецензії</header>
<h1><?= $row['title'] ?></h1>
<table>
    <tr>
        <th>#</th>
        <th>Показник</th>
        <th>Бали</th>
    </tr>
    <tr>
        <td>1</td>
        <td>Актуальність проблеми</td>
        <td><?= $row['actual'] ?></td>
    </tr>
    <tr>
        <td>2</td>
        <td>Новизна та оригінальність ідей</td>
        <td><?= $row['original'] ?></td>
    </tr>
    <tr>
        <td>3</td>
        <td>Використані методи дослідження</td>
        <td><?= $row['methods'] ?></td>
    </tr>
    <tr>
        <td>4</td>
        <td>Теоретичні наукові результати</td>
        <td><?= $row['theoretical'] ?></td>
    </tr>
    <tr>
        <td>5</td>
        <td>Практична направленість результатів<br>(документальне підтвердження впровадження результатів роботи)</td>
        <td><?= $row['practical'] ?></td>
    </tr>
    <tr>
        <td>6</td>
        <td>Рівень використання наукової літератури та інших джерел інформації</td>
        <td><?= $row['literature'] ?></td>
    </tr>
    <tr>
        <td>7</td>
        <td>Ступінь самостійності роботи</td>
        <td><?= $row['selfcontained'] ?></td>
    </tr>
    <tr>
        <td>8</td>
        <td>Якість оформлення</td>
        <td><?= $row['design'] ?></td>
    </tr>
    <tr>
        <td>9</td>
        <td>Наукові публікації</td>
        <td><?= $row['publication'] ?></td>
    </tr>
    <tr>
        <td>10</td>
        <td>Відповідність роботи Державній програмі<br>пріоритетних напрямків інноваційної діяльності</td>
        <td><?= $row['government'] ?></td>
    </tr>
    <tr>
        <td>11</td>
        <td>Відповідність роботи сучасним світовим тенденціям розвитку<br> електроенергетики, електротехніки та
            електромеханіки.
        </td>
        <td><?= $row['tendentious'] ?></td>
    </tr>
    <tr>
        <td>12</td>
        <td>Сума.</td>
        <td><?= $row['sumball'] ?></td>
    </tr>
</table>
<fieldset>
    <legend>Зауваження та недоліки</legend>
    <p>
        <?=(htmlspecialchars($row['defects'])); ?>
    </p>
</fieldset>
<?php $conclusion = ($row['conclusion'] == 0) ? 'Не рекомендується ' : 'Рекомендується'; ?>
<p>Загальний висновок : <?= $conclusion ?> для захисту на науково-практичній конференції</p>


